<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../public/models/User.php';
require_once __DIR__ . '/../public/models/Gift.php';
require_once __DIR__ . '/../public/models/GiftTransaction.php';
require_once __DIR__ . '/../public/helpers/DbConnection.php';

class GiftTransactionTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $db = new DbConnection();
        $db->executeCommand('SET FOREIGN_KEY_CHECKS = 0');
        $db->executeCommand('truncate table gift_transaction');
        $db->executeCommand('truncate table gift_transaction_log');
        $db->executeCommand('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function sendGiftDataProvider()
    {
        return [
            [1, 2, 2, true],
            [2, 3, 4, true],
            [1, 2, 4, false],
            [3, 1, 1, true],
            [4, 2, 3, true],
        ];
    }

    public function dailyGiftDataProvider()
    {
        return [
            [1, 2, true],
            [3, 2, false]
        ];
    }

    /**
     * @dataProvider sendGiftDataProvider
     */
    public function testSendGift($senderId, $receiverId, $giftId, $result)
    {
        $sender = User::findOne(['id' => $senderId]);
        $receiver = User::findOne(['id' => $receiverId]);
        $gift = Gift::findOne(['id' => $giftId]);

        $this->assertSame($result, GiftTransaction::sendGift($sender, $receiver, $gift));
    }

    /**
     * @dataProvider dailyGiftDataProvider
     */
    public function testIsDailyGiftSent($senderId, $receiverId, $expected)
    {
        $sender = User::findOne(['id' => $senderId]);
        $receiver = User::findOne(['id' => $receiverId]);
        $this->assertSame(GiftTransaction::isDailyGiftSent($sender, $receiver), $expected);
    }

    public function testClaim()
    {
        $giftTransaction = GiftTransaction::findOne([]);
        $this->assertTrue($giftTransaction->claim());
    }

    public function testIsExpired()
    {
        $giftTransaction = new GiftTransaction();
        $giftTransaction->created_at = date('Y-m-d H:i:s', time() - (GiftTransaction::GIFT_EXPIRY_DURATION+1));
        $giftTransaction->status = GiftTransaction::STATUS_SENT;
        $this->assertTrue($giftTransaction->isExpired());
    }

    public function testSendGiftToSelf()
    {
        $this->expectErrorMessage('User cannot send gift to self');
        $user = new User();
        $user->id = 1;
        $gift = new Gift();
        $gift->id = 1;
        GiftTransaction::sendGift($user, $user, $gift);
    }
}