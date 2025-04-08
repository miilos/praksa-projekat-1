<?php

namespace App\Controllers;

// Ovo mozda prebaciti u posebnu klasu posto nije Controller
class SessionController
{
    // Ovo mozda da bude staticki
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