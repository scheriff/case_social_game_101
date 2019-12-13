<?php

require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/GiftTransaction.php';

Response::respondIfMethodNotAllowed('POST');

$session = new Session();

$currentUser = null;
Response::respondIfUserNotLoggedIn($session, $currentUser);

$receiverUsername = GiftTransaction::validate($_POST['receiver']);
$receiverUser = User::findOne(['username' => $receiverUsername]);
if (!$receiverUser) {
    Response::asJSON(['message' => 'Invalid User'], 400, 'Bad Request');
}

$giftName = Gift::validate($_POST['gift']);
$gift = Gift::findOne(['name' => $giftName]);
if (!$gift) {
    Response::asJSON(['message' => 'Invalid Gift'], 400, 'Bad Request');
}

try {
    $result = GiftTransaction::sendGift($currentUser, $receiverUser, $gift);
    $message = $result ? 'Sent!' : '1 gift per day!';
    Response::asJSON(['message' => $message]);
} catch (Exception $ex) {
    Response::asJSON(['message' => $ex->getMessage()], 400, 'Bad Request');
}