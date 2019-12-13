<?php

require_once __DIR__ . '/../models/User.php';
require_once 'Cache.php';

class Scoreboard
{
    const ACTION_SEND_GIFT = 1;
    const ACTION_CLAIM_GIFT = 2;
    const ACTION_EXPIRED_GIFT = 3;

    const SCORE_SEND_GIFT = 20;
    const SCORE_CLAIM_GIFT = 10;
    const SCORE_EXPIRED_GIFT = -5;

    const CACHE_KEY = 'weekly_scoreboard';

    public static function getScoreForAction($action)
    {
        switch ($action) {
            case self::ACTION_SEND_GIFT :
                $score = self::SCORE_SEND_GIFT;
                break;
            case self::ACTION_CLAIM_GIFT :
                $score = self::SCORE_CLAIM_GIFT;
                break;
            case self::ACTION_EXPIRED_GIFT :
                $score = self::SCORE_EXPIRED_GIFT;
                break;
            default :
                $score = 0;
        }
        return $score;
    }

    public static function updateUserScoreForAction(User $user, $action)
    {
        $score = self::getScoreForAction($action);
        if($score != 0) {
            $cache = new Cache();
            $cache->client->zincrby(self::CACHE_KEY, $score, $user->username);
        }
    }

    public static function getUserScore(User $user)
    {
        $cache = new Cache();
        return $cache->client->zscore(self::CACHE_KEY, $user->username);
    }

    public static function getTopUsers($limit, $offset)
    {
        $cache = new Cache();
        return $cache->client->zrevrange(self::CACHE_KEY, $offset, $limit);
    }
}