<?php

namespace App\Managers;

class SuccessManager
{
    public static function redirectToSuccessPage($msg): void
    {
        header('Location: /praksa-projekat-1/src/Pages/success.php?msg=' . $msg);
        exit();
    }

    public static function getSuccessMessages(): array
    {
        return [
            'sent-application' => [ 'pageHeader' => 'Uspesna prijava!', 'text' => 'Vasa prijava je uspesno poslata poslodavcu!' ],
            'job-created' => [ 'pageHeader' => 'Oglas kreiran!', 'text' => 'Uspesno ste kreirali oglas za posao!' ],
            'update-success' => [ 'pageHeader' => 'Oglas izmenjen!', 'text' => 'Uspesno ste izmenili oglas!' ],
        ];
    }
}