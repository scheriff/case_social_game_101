<?php

require_once __DIR__ . "/../models/User.php";
require_once __DIR__ . "/../helpers/Session.php";
require_once __DIR__ . "/../helpers/Response.php";

Response::respondIfMethodNotAllowed('POST');

$session = new Session();
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    Response::asJSON(['message' => 'Missing information!'], 400, 'Bad Request');
}

$username = User::validate($_POST['username']);
$user = User::findOne(['username' => $username]);
if ($user === false) {
    $user = new User();
    $user->username = $username;
    $user->setPassword($_POST['password']);
    if ($user->save()) {
        $session->loginUser($user, $_POST['password']);
        Response::asJSON(['redirect' => 'members.php']);
    }
} else {
    if ($session->loginUser($user, $_POST['password'])) {
        Response::asJSON(['redirect' => 'members.php']);
    } else {
        Response::asJSON(['message' => 'Username or password is wrong!']);
    }
}