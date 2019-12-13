<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../public/models/Gift.php';
require_once __DIR__ . '/../public/helpers/DbConnection.php';

class GiftTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $db = new DbConnection();
        $db->executeCommand('SET FOREIGN_KEY_CHECKS = 0');
        $db->executeCommand('truncate table gift');
        $db->executeCommand('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function giftDataProvider()
    {
        $data = [];
        for($i=1;$i<=5;$i++) {
            $data[] = [
                'gift'.$i, (string)$i
            ];
        }
        return $data;
    }

    /**
     * @dataProvider giftDataProvider
     */
    public function testSave($name, $id)
    {
        $gift = new Gift();
        $gift->name = $name;
        $gift->save();
        $this->assertSame($id, $gift->id);
    }
}