<?php
require_once 'helpers/Session.php';
require_once 'models/Gift.php';
require_once 'models/GiftTransaction.php';
require_once 'helpers/Scoreboard.php';

$session = new Session();
$currentUser = $session->getUser();
if (!$currentUser) {
    header("Location: index.php");
    exit;
}
$gifts = Gift::findAll([]);
$unclaimedGifts = GiftTransaction::findUnclaimedGiftsForUser($currentUser);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome To Gifts</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/members.js?<?= md5(microtime()) ?>"></script>
    <style type="text/css">
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
<div>
    <?php include '_navigation.php'; ?>
    <div style="float:left; width: 40%">
    <?php if (count($unclaimedGifts) > 0) { ?>
        <table style="width: 100%; margin-top:20px">
            <tr>
                <th colspan="3">My Unclaimed Gifts</th>
            </tr>
            <tr>
                <th>Sender User ID</th>
                <th>Gift</th>
                <th>Claim</th>
            </tr>
            <?php foreach ($unclaimedGifts as $unclaimedGift) {
                /* @var $unclaimedGift GiftTransaction */ ?>
                <tr class="gift-row" data-id="<?= $unclaimedGift->id ?>">
                    <th><?= $unclaimedGift->sender_id ?></th>
                    <th><?= $unclaimedGift->gift_id ?></th>
                    <th>
                        <button class="claim-gift">Claim</button>
                    </th>
                </tr>
            <?php } ?>
        </table>

    <?php } ?>
    <table style="width: 100%; margin-top:20px">
        <tr>
            <th colspan="3">All Members</th>
        </tr>
        <tr>
            <th>Username</th>
            <th>Gifts</th>
            <th>Send Gift</th>
        </tr>
        <?php foreach (User::findAll([], 10, 0) as $user) {
            /* @var $user User */ ?>
            <?php if ($user->id == $currentUser->id) {
                continue;
            } ?>
            <tr class="user-row" data-username="<?= $user->username ?>">
                <th><?= $user->username ?></th>
                <th>
                    <select class="gift-list">
                        <option value="0">Select a Gift</option>
                        <?php foreach ($gifts as $gift) { ?>
                            <option value="<?= $gift->name ?>"><?= ucwords(str_replace('_', ' ', $gift->name)) ?></option>
                        <?php } ?>
                    </select>
                </th>
                <th>
                    <button class="send-gift">Send</button>
                </th>
            </tr>
        <?php } ?>
    </table>
    </div>
    <div style="float:right; width: 40%">
        <table style="width: 100%; margin-top:20px">
            <tr>
                <th>Username</th>
                <th>Score</th>
            </tr>
            <?php foreach(Scoreboard::getTopUsers(20, 0) as $username => $score) { ?>
                <tr>
                    <th><?= $username ?></th>
                    <th><?= $score ?></th>
                </tr>
            <?php }?>
        </table>
    </div>
</div>
</body>
</html>

