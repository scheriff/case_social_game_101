<?php

require_once 'BaseDBModel.php';
require_once 'GiftTransaction.php';

class GiftTransactionLog extends BaseDBModel
{
    public $gift_transaction_id;
    public $status;

    public static function tableName()
    {
        return 'gift_transaction_log';
    }

    public static function saveChange(GiftTransaction $giftTransaction)
    {
        $log = new GiftTransactionLog();
        $log->gift_transaction_id = $giftTransaction->id;
        $log->status = $giftTransaction->status;
        if($log->save()) {
            return $log;
        }
    }
}