<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style/style.css">
    <title>Error</title>
</head>
<body>
    <div class="error-container">
        <h1 class="error-title">Error!</h1>
        <p>
            <?php
                require_once __DIR__ . '/../../vendor/autoload.php';

                if (isset($_GET['err'])) {
                    echo \App\Controllers\ErrorController::getErrors()[$_GET['err']];
                }
                else {
                    echo 'Nepoznata greska!';
                }
            ?>
        </p>
    </div>
</body>
</html>