<?php

use App\Core\Request;
use App\Pages\FormRenderer;
use App\Controllers\AuthController;

require_once __DIR__ . '/../../vendor/autoload.php';

    $formRenderer = new FormRenderer();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $request = new Request();
        $data = $request->getBody();
        $errors = [];

        $authController = new AuthController();

        if (!$authController->logIn($data)) {
            $errors['email'][] = 'Incorrect email or password!';
            $errors['password'][] = 'Incorrect email or password!';
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
    <title>Log in</title>
</head>
<body>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form" autocomplete="off">
        <h1 class="form-title">Log in</h1>

        <?php
            echo $formRenderer->renderFormField('<input type="text" id="email" name="email" placeholder="Email adresa" class="input">', $errors['email'] ?? null);
            echo $formRenderer->renderFormField('<input type="password" id="password" name="password" placeholder="Password" class="input">', $errors['password'] ?? null);
            ?>

        <input type="submit" value="Log in" class="form-btn">
    </form>
</body>
</html>