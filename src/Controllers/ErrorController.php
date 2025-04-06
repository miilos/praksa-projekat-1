<?php

namespace App\Controllers;

// each part of the app will grab the error message from the getErrors() method
// and then call the redirectToErrorPage() method with the given message
class ErrorController
{
    public static function redirectToErrorPage($err): void
    {
        header('Location: ../Pages/error.php?err=' . $err);
        exit();
    }

    public static function getErrors(): array
    {
        return [
          'email-taken' => 'Ova email adresa je zauzeta!',
            'db-error' => 'Greska pri povezivanju sa bazom!'
        ];
    }
}