<?php

use App\Managers\SessionManager;

require_once __DIR__ . '../../../vendor/autoload.php';

$user = SessionManager::getSessionData('user');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

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
    <title>Prijavite se za posao</title>
</head>
<body>
    <?php
        include_once './_navbar.php';

        if (!$user) {
            echo '
                <div class="not-logged-in">
                    <h1>Niste ulogovani!</h1>
                    <p>Ulogujte se da biste poslali prijavu</p>
                    <a href="./login.php" class="btn btn--primary">Log in</a>
                </div>
            ';
        }
        else {
            echo '
                <form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" class="form" autocomplete="off">
                    <h1>Vasi podaci za prijavu na oglas:</h1>
                    <input type="hidden" class="input" value="' . $user['userId'] . '" name="userId">
                    <input type="hidden" class="input" value="' . $_GET['jobId'] . '" name="jobId">
            
                    <div class="input-container">
                        <input type="text" id="firstName" name="firstName" value="' . $user['firstName'] . '" class="input" disabled>
                    </div>
            
                    <div class="input-container">
                        <input type="text" id="lastName" name="lastName" value="' . $user['lastName'] . '" class="input" disabled>
                    </div>
            
                    <div class="input-container">
                        <input type="text" id="email" name="email" value="' . $user['email'] . '" class="input" disabled>
                    </div>
            
                    <input type="submit" name="submit" class="form-btn" value="Prijavite se">
                </form>
            ';
        }
    ?>
</body>
</html>
