<?php

namespace App\Controllers;

class SessionController
{
    public function startSession($key, $value): void
    {
        session_start();
        $_SESSION[$key] = $value;
    }

    public function getSessionData($key): mixed
    {
        if (!(session_status() === PHP_SESSION_ACTIVE)) {
            session_start();
        }
        return $_SESSION[$key];
    }
}