<?php

namespace App\Pages;

class FormRenderer
{
    public function renderFormField($inputFieldHtml, $errors): string
    {
        if(empty($errors)) {
            return '<div class="input-container">' . $inputFieldHtml . '</div>';
        }
        else {
            $errorFieldHtml = str_replace('input', 'input input--error', $inputFieldHtml);

            $errorMessages = '';
            foreach ($errors as $error) {
                $errorMessages .= '<span class="error-msg">' . $error . '</span><br/>';
            }

            return '<div class="input-container">' . $errorFieldHtml . $errorMessages . '</div>';
        }
    }
}