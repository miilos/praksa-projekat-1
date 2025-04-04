<?php

namespace App\Models;

abstract class Model
{
    protected const RULE_REQUIRED = "required";
    protected const RULE_EMAIL = "email";
    protected const RULE_SELECTED = "selected";
    protected const RULE_MATCHES = "matches";
    protected const RULE_MIN = "min";


    protected function errorMessages() {
        return [
          self::RULE_REQUIRED => "Morate popuniti ovo polje.",
          self::RULE_EMAIL => "Ovo nije validna email adresa.",
            self::RULE_SELECTED => "Morate izabrati jednu od opcija.",
            self::RULE_MATCHES => ""
        ];
    }
}