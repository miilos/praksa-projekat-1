<?php

namespace App\Controllers;

// each part of the app will grab the error message from the getErrors() method
// and then call the redirectToErrorPage() method with the given message
use App\Core\Request;
use App\Core\Response;
use App\Core\Route;
use App\Views\View;

class ErrorController
{
    #[Route(method: 'get', path: 'error/{msg}', name: 'error')]
    public function error(Request $req, Response $res): string
    {
        $msg = $req->getUrlParams()['msg'] ?? null;
        $err = '';

        if ($msg) {
            $err = $this->getErrors()[$msg];
        }

        $view = new View();
        $view->setLayout('error');
        return $view->render('error', [
            'pageTitle' => 'Error',
            'err' => $err,
        ]);
    }

    public static function redirectToErrorPage(string $err): void
    {
        header('Location: /error/' . $err);
        exit();
    }

    private function getErrors(): array
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

    public static function handleAPIError(Response $res, \Throwable $t, int $statusCode = 500): string
    {
        return $res->statusCode($statusCode)->sendJSON([
            'status' => 'error',
            'message' => $t->getMessage(),
        ]);
    }
}