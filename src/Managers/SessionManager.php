<?php

namespace App\Managers;

class SessionManager
{
    public static function startSession(string $key, mixed $value): void
    {
        session_start();
        $_SESSION[$key] = $value;
    }

    public static function getSessionData(string $key): mixed
    {
        if (!(session_status() === PHP_SESSION_ACTIVE)) {
            session_start();
        }
        return $_SESSION[$key] ?? null;
    }
}