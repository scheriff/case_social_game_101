<?php

require_once 'BaseDBModel.php';

class User extends BaseDBModel
{
    public $username;
    public $password_hash;
    public $daily_limit_reset_at;

    public static function tableName()
    {
        return 'user';
    }

    public function load($data)
    {
        $this->username = isset($data['username']) ? $this->validate($data['username']) : null;
    }

    public function setPassword($password)
    {
        $this->password_hash = password_hash(self::validate($password), PASSWORD_BCRYPT);
    }

    public function checkPassword($password)
    {
        return password_verify(self::validate($password), $this->password_hash);
    }

    public function resetDailyLimit()
    {
        $this->daily_limit_reset_at =  date('Y-m-d H:i:s', time());
        $this->save();
    }
}