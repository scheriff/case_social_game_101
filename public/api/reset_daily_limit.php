<?php

require_once __DIR__ . '/../helpers/Response.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../models/User.php';

Response::respondIfMethodNotAllowed('POST');

$session = new Session();

$currentUser = null;
Response::respondIfUserNotLoggedIn($session, $currentUser);

if (!isset($_POST['username'])) {
    Response::asJSON(['message' => 'Missing information!'], 400, 'Bad Request');
}

$username = User::validate($_POST['username']);
$user = User::findOne(['username' => $username]);
if ($user === false) {
    Response::asJSON(['message' => 'User does not exist!']);
}
$user->resetDailyLimit();
Response::asJSON(['message' => 'Done']);