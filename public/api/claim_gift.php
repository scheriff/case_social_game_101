<?php

require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/GiftTransaction.php';

Response::respondIfMethodNotAllowed('POST');

$session = new Session();

$currentUser = null;
Response::respondIfUserNotLoggedIn($session, $currentUser);

$giftTransactionId = GiftTransaction::validate($_POST['giftTransactionId']);
$giftTransaction = GiftTransaction::findOneToClaim($giftTransactionId, $currentUser);

if (!$giftTransaction) {
    Response::asJSON(['message' => 'Invalid Claim'], 400, 'Bad Request');
} else {
    try {
        $result = $giftTransaction->claim();
        if ($result) {
            Response::asJSON(['message' => 'Claimed!']);
        } else {
            Response::asJSON(['message' => 'Expired!']);
        }
    } catch (Exception $ex) {
        Response::asJSON(['message' => $ex->getMessage()], 400, 'Bad Request');
    }
}