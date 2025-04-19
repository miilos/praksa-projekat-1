<?php

use App\Managers\SessionManager;
use App\Managers\ErrorManager;
use App\Models\JobModel;
use App\Controllers\EmployerController;
use App\Pages\FormRenderer;

require_once __DIR__ . '/../../vendor/autoload.php';

$user = SessionManager::getSessionData('user');

if (!$user || $user['role'] !== 'admin') {
    ErrorManager::redirectToErrorPage('not-authorized');
}

if (!$_GET['id']) {
    ErrorManager::redirectToErrorPage('bad-job-id');
}

$job = JobModel::getJobById($_GET['id']);

if (!$job) {
    ErrorManager::redirectToErrorPage('bad-job-id');
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
    <title>Update job</title>
</head>
<body>
    <?php include './_navbar.php' ?>

    <form method="post" action="./executeUpdate.php?id=<?= $job['jobId'] ?>" class="form" autocomplete="off">
        <h1 class="form-title">Azuriraj oglas</h1>

        <?php
        $employers = '';
        foreach (EmployerController::getAllEmployers() as $employer) {
            if ($employer['employerId'] === $job['employerId']) {
                $employers .= '<option value="' . $employer['employerId'] . '" selected>' . $employer['employerName'] . '</option>';
            }
            else {
                $employers .= '<option value="' . $employer['employerId'] . '">' . $employer['employerName'] . '</option>';
            }
        }

        echo FormRenderer::renderFormField('
                <select name="employerId" class="input" disabled>
                    <option value="-">Izaberite poslodavca</option>
                    ' . $employers . '
                </select>
            ');
        echo FormRenderer::renderFormField('<input type="text" id="jobName" name="jobName" placeholder="Naziv oglasa" class="input" value="' . $job['jobName'] . '">');
        echo FormRenderer::renderFormField('<textarea id="description" name="description" placeholder="Opis oglasa" class="input">' . $job['description'] . '</textarea>');
        echo FormRenderer::renderFormField('<input type="text" id="field" name="field" placeholder="Polje rada" class="input" value="' . $job['field'] . '" disabled>');
        echo FormRenderer::renderFormField('<input type="number" id="startSalary" name="startSalary" placeholder="Pocetna plata" class="input" value="' . $job['startSalary'] . '">');
        echo FormRenderer::renderFormField('
                <select name="shifts" class="input">
                    <option ' . ($job['shifts'] === 1 ? 'selected' : '') . ' value="1">1</option>
                    <option ' . ($job['shifts'] === 2 ? 'selected' : '') . ' value="2">2</option>
                    <option ' . ($job['shifts'] === 3 ? 'selected' : '') . ' value="3">3</option>
                </select>
            ');
        echo FormRenderer::renderFormField('<input type="text" id="location" name="location" placeholder="Lokacija" class="input" value="' . $job['location'] . '">');
        echo FormRenderer::renderFormField('
                <input type="checkbox" id="flexibleHours" name="flexibleHours" ' . ($job['flexibleHours'] ? 'checked' : '') . '>
                <label for="flexibleHours">Klizno radno vreme</label>
            ');
        echo FormRenderer::renderFormField('
                <input type="checkbox" id="workFromHome" name="workFromHome" ' . ($job['workFromHome'] ? 'checked' : '') . '>
                <label for="workFromHome">Rad od kuce</label>    
            ');
        ?>
        <input type="submit" value="Azurirajte oglas" class="form-btn">
    </form>
</body>
</html>
