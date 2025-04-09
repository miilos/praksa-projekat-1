<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../style/style.css">
    <title>Uspeh</title>
</head>
<body>
<div class="success-container">
    <?php
        require_once __DIR__ . '/../../vendor/autoload.php';

        if (isset($_GET['msg'])) {
            $msg = $_GET['msg'];
            $header = \App\Managers\SuccessManager::getSuccessMessages()[$msg]['pageHeader'];
            $text = \App\Managers\SuccessManager::getSuccessMessages()[$msg]['text'];
        }
    ?>

    <h1 class="success-title"><?= $header ?></h1>
    <p><?= $text ?></p>
    <a href="/praksa-projekat-1/index.php" class="btn btn--primary">Nazad na home</a>
</div>
</body>
</html>