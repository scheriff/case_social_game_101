<?php

class DbConnection
{
    public $pdo;

    protected function initConnection()
    {
        try {
            $pdo = new PDO('mysql:host=' . getenv('DB_HOST') . ';dbname=' . getenv('DB_NAME'), getenv('DB_USER'), getenv('DB_PASSWORD'));
            $this->pdo = $pdo;
        } catch (PDOException $ex) {
            throw new PDOException($ex->getMessage(), (int)$ex->getCode());
        }
    }

    protected function prepareWhere($conditions = [])
    {
        if (empty($conditions)) {
            return ['', []];
        }

        $where = '';
        $params = [];
        foreach ($conditions as $column => $value) {
            $where .= (empty($where) ? ' where ' : ' and ') . $column . '=?';
            $params[] = $value;
        }
        return [$where, $params];
    }

    public function executeCommand($sql)
    {
        if (is_null($this->pdo)) {
            $this->initConnection();
        }
        /* @var $pdo PDO */
        $pdo = $this->pdo;
        $pdo->exec($sql);
    }

    public function queryRaw($sql, $params)
    {
        if (is_null($this->pdo)) {
            $this->initConnection();
        }
        /* @var $pdo PDO */
        $pdo = $this->pdo;
        $statement = $pdo->prepare($sql);
        $statement->execute($params);
        //TODO optimize fetch for high number of rows
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $rows;
    }

    public function query($table, $conditions = [], $columns = [], $limit = 0, $offset = 0)
    {
        if (is_null($this->pdo)) {
            $this->initConnection();
        }
        /* @var $pdo PDO */
        $pdo = $this->pdo;
        $select = empty($columns) ? '*' : implode(',', $columns);

        list($where, $params) = $this->prepareWhere($conditions);

        $sql = 'select ' . $select . ' from ' . $table . $where;
        if ($limit != 0) {
            $sql .= ' limit ' . $limit . ' offset ' . $offset;
        }
        return $this->queryRaw($sql, $params);
    }

    public function insert($table, $columns)
    {
        if (is_null($this->pdo)) {
            $this->initConnection();
        }
        /* @var $pdo PDO */
        $pdo = $this->pdo;

        $columnSql = ' (' . implode(',', array_keys($columns)) . ') ';
        $valueSql = '';
        for ($i = 1; $i <= count($columns); $i++) {
            $valueSql .= (empty($valueSql) ? ' values( ' : ' , ') . '?';
        }
        $valueSql .= ')';

        $sql = 'insert into ' . $table . $columnSql . $valueSql;
        $statement = $pdo->prepare($sql);
        $result = $statement->execute(array_values($columns));
        if ($statement->errorCode() != '00000') {
            error_log(sprintf('%s - %s', $statement->errorCode(), implode(" ", $statement->errorInfo())));
            return false;
        }
        return $result ? $pdo->lastInsertId('primary') : false;
    }

    public function update($table, $values, $conditions = [])
    {
        if (is_null($this->pdo)) {
            $this->initConnection();
        }
        /* @var $pdo PDO */
        $pdo = $this->pdo;

        $valueParams = [];
        $updateSql = '';
        foreach ($values as $column => $value) {
            $updateSql .= (empty($updateSql) ? ' set ' : ' , ') . $column . '=?';
            $valueParams[] = $value;
        }
        list($where, $conditionParams) = $this->prepareWhere($conditions);
        $params = array_merge($valueParams, $conditionParams);
        $statement = $pdo->prepare('update ' . $table . $updateSql . $where);
        $statement->execute($params);
        return $statement->rowCount();
    }
}