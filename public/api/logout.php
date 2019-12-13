<?php

require_once __DIR__ . "/../helpers/Session.php";
require_once __DIR__ . "/../helpers/Response.php";

Response::respondIfMethodNotAllowed('POST');

$session = new Session();
$session->logoutUser();
Response::asJSON(['redirect' => 'index.php']);