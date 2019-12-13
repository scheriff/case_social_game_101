<?php

require_once 'BaseDBModel.php';

class Gift extends BaseDBModel
{
    public $name;

    public static function tableName()
    {
        return 'gift';
    }
}