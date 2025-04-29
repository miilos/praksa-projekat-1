<?php

namespace App\Controllers;

use App\Core\Request;
use App\Views\View;

class SuccessController
{
    public function success(Request $req): string
    {
        $msg = $req->getUrlParams()['msg'] ?? null;
        $header = '';
        $text = '';

        if ($msg) {
            $header = $this->getSuccessMessages()[$msg]['pageHeader'];
            $text = $this->getSuccessMessages()[$msg]['text'];
        }

        $view = new View();
        $view->setLayout('success');
        return $view->render('success', [
            'pageTitle' => 'Uspeh',
            'header' => $header,
            'text' => $text,
        ]);
    }

    public static function redirectToSuccessPage($msg): void
    {
        header('Location: /success/' . $msg);
        exit();
    }

    private function getSuccessMessages(): array
    {
        return [
            'sent-application' => [ 'pageHeader' => 'Uspesna prijava!', 'text' => 'Vasa prijava je uspesno poslata poslodavcu!' ],
            'job-created' => [ 'pageHeader' => 'Oglas kreiran!', 'text' => 'Uspesno ste kreirali oglas za posao!' ],
            'update-success' => [ 'pageHeader' => 'Oglas izmenjen!', 'text' => 'Uspesno ste izmenili oglas!' ],
            'delete-success' => [ 'pageHeader' => 'Oglas je obrisan!', 'text' => 'Uspesno ste obrisali oglas!' ],
        ];
    }
}