<?php

namespace App\Pages;

class FormRenderer
{
    // if there are no validation errors, render the container and the passed form field html
    // if there are errors, add the input--error class to the passed html and render the field along with the errors
    public function renderFormField(string $inputFieldHtml, array $errors): string
    {
        if (empty($errors)) {
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