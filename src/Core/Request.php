<?php

namespace App\Core;

class Request
{
    private function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getBody(): array
    {
        $body = [];

        if ($this->getMethod() === "GET") {
            foreach ($_GET as $key => $value) {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        else if ($this->getMethod() === "POST") {
            foreach ($_POST as $key => $value) {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }

        return $body;
    }
}