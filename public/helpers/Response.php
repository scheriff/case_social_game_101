<?php

require_once 'Session.php';

class Response
{
    public static function asJSON($response, $code = 200, $message = 'OK')
    {
        header(sprintf('HTTP/1.1 %d %s', $code, $message));
        header('Content-Type: application/json');

        $jsonResponse = json_encode($response);
        if ($jsonResponse === false) {
            $jsonResponse = '{"error": "unknown"}';
        }
        echo $jsonResponse;
        exit;
    }

    public static function respondIfMethodNotAllowed($method)
    {
        if ($_SERVER['REQUEST_METHOD'] != $method) {
            Response::asJSON(['message' => 'Only ' . $method . ' method is allowed!'], 403, 'Forbidden');
        }
    }

    public static function respondIfUserNotLoggedIn(Session $session, &$currentUser)
    {
        $currentUser = $session->getUser();
        if (is_null($currentUser)) {
            Response::asJSON(['message' => 'User should be logged in!'], 401, 'Unauthorized');
        }
    }
}