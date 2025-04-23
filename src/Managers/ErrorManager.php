<?php

namespace App\Managers;

// each part of the app will grab the error message from the getErrors() method
// and then call the redirectToErrorPage() method with the given message
class ErrorManager
{
    public static function redirectToErrorPage(string $err): void
    {
        header('Location: /src/Pages/error.php?err=' . $err);
        exit();
    }

    public static function getErrors(): array
    {
        return [
          'email-taken' => 'Ova email adresa je zauzeta',
            'db-error' => 'Greska pri povezivanju sa bazom',
            'bad-job-id' => 'Oglas koji trazite ne postoji',
            'failed-application' => 'Vasa prijava nije poslata. Probajte ponovo',
            'not-authorized' => 'Nemate dozvolu da pristupite ovoj stranici',
            'unknown-error' => 'Nesto ne radi',
            'update-error' => 'Greska pri azuriranju',
            'delete-error' => 'Greska pri brisanju',
        ];
    }
}