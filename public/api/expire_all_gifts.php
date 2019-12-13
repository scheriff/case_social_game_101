<?php

require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/GiftTransaction.php';

Response::respondIfMethodNotAllowed('POST');

$session = new Session();

$currentUser = null;
Response::respondIfUserNotLoggedIn($session, $currentUser);

GiftTransaction::expireAllUnclaimedGifts();
Response::asJSON(['message' => 'Done']);