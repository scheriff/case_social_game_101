<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../public/helpers/Scoreboard.php';
require_once __DIR__ . '/../public/helpers/DbConnection.php';

class ScoreboardTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $cache = new Cache();
        $cache->client->del([Scoreboard::CACHE_KEY]);
    }

    public function scoreDataProvider()
    {
        return [
            //username / action / expected score
            ['cem', Scoreboard::ACTION_SEND_GIFT, Scoreboard::SCORE_SEND_GIFT],
            ['can', Scoreboard::ACTION_CLAIM_GIFT, Scoreboard::SCORE_CLAIM_GIFT],
            ['barkin', 4, 0]
        ];
    }

    /**
     * @dataProvider scoreDataProvider
     */
    public function testUpdateUserScoreForAction($username, $action, $score)
    {
        $user = new User();
        $user->username = $username;
        Scoreboard::updateUserScoreForAction($user, $action);
        $this->assertSame($score, (int)Scoreboard::getUserScore($user));
    }
}