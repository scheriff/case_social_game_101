<?php

require __DIR__ . "/../helpers/DbConnection.php";

class BaseDBModel
{
    public $id;
    public $created_at;
    public $is_new_record = true;

    public static function validate($value)
    {
        return filter_var(trim($value), FILTER_SANITIZE_STRING);
        //return htmlspecialchars(stripslashes(trim($value)));
    }

    public static function findOne($conditions)
    {
        $data = self::findAll($conditions, 1);
        $first = current($data);
        return $first;
    }

    public static function findAll($conditions, $limit = 0, $offset = 0)
    {
        $db = new DbConnection();
        $rows = $db->query(static::tableName(), $conditions, [], $limit, $offset);
        $data = [];
        foreach($rows as $row) {
            $data[] = static::mapRowToModel($row);
        }
        return $data;
    }

    public function save()
    {
        $columns = get_object_vars($this);
        foreach ($columns as $column => $value) {
            if(is_null($value) || $column == 'is_new_record') {
                unset($columns[$column]);
            }
        }
        $db = new DbConnection();
        if($this->is_new_record) {
            $insertId = $db->insert(static::tableName(), $columns);
            if($insertId !== false) {
                $this->id = $insertId;
                return true;
            }
            return false;
        } else {
            unset($columns['id']);
            return $db->update(static::tableName(), $columns, ['id' => $this->id]);
        }
    }

    public static function mapRowToModel($data)
    {
        $model = new static();
        foreach($data as $column => $value) {
            $model->$column = $value;
        }
        $model->is_new_record = false;
        return $model;
    }
}