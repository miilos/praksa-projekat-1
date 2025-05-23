<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <title>{{TITLE}}</title>
</head>
<body>
<?php
include_once './src/Views/_navbar.php';
?>

{{CONTENT}}

<script src="/src/Views/scripts/addFavourites.js"></script>
<script src="/src/Views/scripts/addComment.js"></script>

</body>
</html>