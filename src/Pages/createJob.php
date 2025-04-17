<?php

    use App\Core\Request;
    use App\Managers\SessionManager;
    use App\Managers\ErrorManager;
    use App\Pages\FormRenderer;
    use App\Controllers\EmployerController;
    use App\Controllers\JobController;
    use App\Controllers\LocationController;
    use App\Controllers\FieldOfWorkController;

    require_once __DIR__ . '/../../vendor/autoload.php';

    $user = SessionManager::getSessionData('user');

    if (!$user || $user['role'] !== 'admin') {
        ErrorManager::redirectToErrorPage('not-authorized');
    }

    $errors = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request = new Request();
        $body = $request->getBody();

        $jobController = new JobController();
        $createJobStatus = $jobController->createJob($body);

        if (is_array($createJobStatus)) {
            $errors = $createJobStatus;
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
    <title>Dodaj oglas</title>
</head>
<body>
    <?php include_once './_navbar.php'; ?>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form" autocomplete="off">
        <h1 class="form-title">Kreirajte oglas</h1>

        <?php
            $employers = '';
            foreach (EmployerController::getAllEmployers() as $employer) {
                $employers .= '<option value="' . $employer['employerId'] . '">' . $employer['employerName'] . '</option>';
            }

            echo FormRenderer::renderFormField('
                <select name="employerId" class="input">
                    <option value="-">Izaberite poslodavca</option>
                    ' . $employers . '
                </select>
            ', $errors['employerId'] ?? null);
            echo FormRenderer::renderFormField('<input type="text" id="jobName" name="jobName" placeholder="Naziv oglasa" class="input">', $errors['jobName'] ?? null);
            echo FormRenderer::renderFormField('<textarea id="description" name="description" placeholder="Opis oglasa" class="input"></textarea>', $errors['description'] ?? null);

            $fields = FieldOfWorkController::getAllFields();
            $fieldsHtml = '<option value="-">Izaberite polje rada</option>';
            foreach ($fields as $field) {
                $fieldsHtml .= '<option value="' . $field . '">' . ucfirst($field) . '</option>';
            }

            echo FormRenderer::renderFormField('<select name="field" class="input">' . $fieldsHtml . '</select>', $errors['field'] ?? null);
            echo FormRenderer::renderFormField('<input type="number" id="startSalary" name="startSalary" placeholder="Pocetna plata" class="input">', $errors['startSalary'] ?? null);
            echo FormRenderer::renderFormField('
                <select name="shifts" class="input">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            ', $errors['shifts'] ?? null);

            $locations = LocationController::getAllLocations();
            $locationsHtml = '<option value="-">Izaberite grad</option>';
            foreach ($locations as $location) {
                $locationsHtml .= '<option value="' . $location . '">' . $location . '</option>';
            }

            echo FormRenderer::renderFormField('<select class="input" name="location">' . $locationsHtml . '</select>', $errors['location'] ?? null);
            echo FormRenderer::renderFormField('
                <input type="checkbox" id="flexibleHours" name="flexibleHours">
                <label for="flexibleHours">Klizno radno vreme</label>    
            ', $errors['flexibleHours'] ?? null);
            echo FormRenderer::renderFormField('
                <input type="checkbox" id="workFromHome" name="workFromHome">
                <label for="workFromHome">Rad od kuce</label>    
            ', $errors['workFromHome'] ?? null);
        ?>
        <input type="submit" value="Kreirajte oglas" class="form-btn">
    </form>
</body>
</html>
