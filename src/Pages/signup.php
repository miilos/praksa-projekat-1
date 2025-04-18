<?php

use App\Controllers\AuthController;
use App\Core\Request;
use App\Pages\FormRenderer;

require_once __DIR__ . '/../../vendor/autoload.php';

Dotenv\Dotenv::createImmutable('../../')->load();

$errors = [];

// if the signup form was submitted, get the request body, validate it and create new user
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request = new Request();
    $data = $request->getBody();

    $errors = [];
    $authController = new AuthController();

    $signupStatus = $authController->signUp($data);
    if (is_array($signupStatus)) {
        $errors = $signupStatus;
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style/style.css">
    <title>Sign up</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form" autocomplete="off">
        <h1 class="form-title">Sign up</h1>

        <?php
            echo FormRenderer::renderFormField('<input type="text" id="firstName" name="firstName" placeholder="Ime" class="input">', $errors['firstName'] ?? null);
            echo FormRenderer::renderFormField('<input type="text" id="lastName" name="lastName" placeholder="Prezime" class="input">', $errors['lastName'] ?? null);
            echo FormRenderer::renderFormField('<input type="text" id="email" name="email" placeholder="Email adresa" class="input">', $errors['email'] ?? null);
            echo FormRenderer::renderFormField('<input type="password" id="password" name="password" placeholder="Password" class="input">', $errors['password'] ?? null);
            echo FormRenderer::renderFormField('<input type="password" id="passwordConfirm" name="passwordConfirm" placeholder="Confirm password" class="input">', $errors['passwordConfirm'] ?? null);
            echo FormRenderer::renderFormField(
                    '<select name="field" class="input">
                            <option value="-">Izaberite polje rada...</option>
                            <option value="it">IT</option>
                            <option value="prodaja">Prodaja</option>
                            <option value="pravo">Pravo</option>
                            <option value="menadzment">Menadzment</option>
                        </select>'
            , $errors['field'] ?? null);
        ?>
        <input type="submit" value="Sign up" class="form-btn">
    </form>
</body>
</html>