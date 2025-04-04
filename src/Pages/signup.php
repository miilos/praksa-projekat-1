<?php

use App\Controllers\AuthController;
require_once __DIR__ . '/../../vendor/autoload.php';

if($_SERVER["REQUEST_METHOD"] === "POST") {
    $authController = new AuthController();
    $authController->signup();
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
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="signup" autocomplete="off">
        <h1 class="signup-title">Sign up</h1>

        <input type="text" id="firstName" name="firstName" placeholder="Ime" class="input">
        <input type="text" id="lastName" name="lastName" placeholder="Prezime" class="input">
        <input type="text" id="email" name="email" placeholder="Email adresa" class="input">
        <input type="password" id="password" name="password" placeholder="Password" class="input">
        <input type="password" id="passwordConfirm" name="passwordConfirm" placeholder="Confirm password" class="input">
        <select name="field" class="input">
            <option value="-">Izaberite polje rada...</option>
            <option value="it">IT</option>
            <option value="prodaja">Prodaja</option>
            <option value="pravo">Pravo</option>
            <option value="menadzment">Menadzment</option>
        </select>

        <input type="submit" value="Sign up" class="signup-btn">
    </form>
</body>
</html>
