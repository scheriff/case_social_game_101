<?php

require_once __DIR__ . '/../helpers/DbConnection.php';
require_once 'BaseDBModel.php';
require_once 'GiftTransactionLog.php';
require_once 'User.php';
require_once 'Gift.php';

class GiftTransaction extends BaseDBModel
{
    public $gift_id;
    public $sender_id;
    public $receiver_id;
    public $status;

    const STATUS_SENT = 1;
    const STATUS_CLAIMED = 2;
    const STATUS_EXPIRED = 3;

    const GIFT_EXPIRY_DURATION = 7*24*60*60; //7 days
    const GIFT_SEND_COOLDOWN_DURATION = '24 HOUR';

    public static function tableName()
    {
        return 'gift_transaction';
    }

    public static function sendGift(User $sender, User $receiver, Gift $gift)
    {
        if($sender->id == $receiver->id) {
            throw new Exception("User cannot send gift to self");
        }
        if(!self::isDailyGiftSent($sender, $receiver)) {
            $giftTransaction = new GiftTransaction();
            $giftTransaction->gift_id = $gift->id;
            $giftTransaction->sender_id = $sender->id;
            $giftTransaction->receiver_id = $receiver->id;
            $giftTransaction->status = self::STATUS_SENT;
            if($giftTransaction->save()) {
                GiftTransactionLog::saveChange($giftTransaction);
                return true;
            } else {
                throw new Exception("Unknown error occurred");
            }
        } else {
            return false;
        }
    }

    public function updateStatus($status)
    {
        if($this->status != $status) {
            $this->status = $status;
            if($this->save()) {
                GiftTransactionLog::saveChange($this);
            }
        }
    }

    public static function isDailyGiftSent(User $sender, User $receiver)
    {
        $params = [$sender->id, $receiver->id];
        $sql = sprintf('select 1 from %s where sender_id = ? and receiver_id = ? and created_at >= NOW() - INTERVAL %s LIMIT 1', self::tableName(), self::GIFT_SEND_COOLDOWN_DURATION);
        $db = new DbConnection();
        $rows = $db->queryRaw($sql, $params);

        return count($rows) > 0 ? true : false;
    }

    public function isExpired()
    {
        $expireDate = date('Y-m-d H:i:s', time() - self::GIFT_EXPIRY_DURATION);
        if($this->status == self::STATUS_EXPIRED || $this->created_at < $expireDate) {
            return true;
        }
        return false;
    }

    public function claim()
    {
        if(!$this->isExpired()) {
            $this->updateStatus(self::STATUS_CLAIMED);
            return true;
        }
        $this->updateStatus(self::STATUS_EXPIRED);
        return false;
    }

    public static function findOneToClaim($giftTransactionId, User $receiver)
    {
        return self::findOne([
            'id' => $giftTransactionId,
            'receiver_id' => $receiver->id,
            'status' => self::STATUS_SENT
        ]);
    }

    public static function findUnclaimedGiftsForUser(User $user)
    {
        $gifts = self::findAll([
            'receiver_id' => $user->id,
            'status' => self::STATUS_SENT
        ]);
        return $gifts;
    }

    public static function claimAllGiftsForUser(User $receiver)
    {
        $gifts = self::findUnclaimedGiftsForUser($receiver);
        foreach($gifts as $gift) {
            /* @var $gift GiftTransaction */
            $gift->claim();
        }
    }

    public static function expireAllUnclaimedGifts()
    {
        $gifts = self::findAll([
            'status' => self::STATUS_SENT
        ]);
        foreach($gifts as $gift) {
            /* @var $gift GiftTransaction */
            $gift->updateStatus(self::STATUS_EXPIRED);
        }
    }
}