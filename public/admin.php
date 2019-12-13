<?php

require_once 'helpers/Session.php';
$session = new Session();
$currentUser = $session->getUser();
if (!$currentUser) {
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome To Gifts</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/admin.js?<?= md5(microtime()) ?>"></script>
</head>
<body>
<?php include '_navigation.php'; ?>
<div>
    <form id="reset-daily-form" action="api/reset_daily_limit.php" method="post">
        <div><input type="text" name="username" placeholder="Username" required></div>
        <div><input type="submit" value="Reset Daily Limit"></input></div>
    </form>
</div>
</body>
</html>

