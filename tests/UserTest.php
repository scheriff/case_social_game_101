<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../public/models/User.php';
require_once __DIR__ . '/../public/helpers/DbConnection.php';

class UserTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $db = new DbConnection();
        $db->executeCommand('SET FOREIGN_KEY_CHECKS = 0');
        $db->executeCommand('truncate table user');
        $db->executeCommand('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function userDataProvider()
    {
        $data = [];
        for($i=1;$i<=5;$i++) {
            $data[] = [
                'test'.$i, 'pass'.$i, (string)$i
            ];
        }
        return $data;
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testSave($username, $password, $id)
    {
        $user = new User();
        $user->username = $username;
        $user->setPassword($password);
        $user->save();
        $this->assertSame($id, $user->id);
        return $user->password_hash;
    }

    public function testCheckPassword()
    {
        $user = new User();
        $user->setPassword('myPassword123?');
        $this->assertTrue($user->checkPassword('myPassword123?'));
    }
}