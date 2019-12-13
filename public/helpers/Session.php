<?php

require_once __DIR__ . '/../models/User.php';

class Session
{
    function __construct()
    {
        session_start();
    }

    public function loginUser(User $user, $password)
    {
        if ($user->checkPassword($password)) {
            $_SESSION['user_id'] = $user->id;
            return true;
        }
        return false;
    }

    public function logoutUser()
    {
        unset($_SESSION['user_id']);
        return true;
    }

    public function getUser()
    {
        if (isset($_SESSION['user_id'])) {
            return User::findOne(['id' => $_SESSION['user_id']]);
        }
        return false;
    }
}