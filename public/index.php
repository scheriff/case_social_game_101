<?php

require_once 'helpers/Session.php';

$session = new Session();
if ($session->getUser()) {
    header("Location: members.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome To Gifts</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/login.js?<?= md5(microtime()) ?>"></script>
</head>
<body>
<div>
    <form id="login-form" action="api/login.php" method="post">
        <div style="margin-bottom: 10px;">
            <label for="username">Username</label>
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div style="margin-bottom: 10px;">
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <input type="submit" value="Login"></input>
    </form>
</div>
</body>
</html>

