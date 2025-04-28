<form method="post" action="/createJob" class="form" autocomplete="off">
    <h1 class="form-title">Kreirajte oglas</h1>

    <?php
    $employersHtml = '';
    foreach ($employers as $employer) {
        $employersHtml .= '<option value="' . $employer['employerId'] . '">' . $employer['employerName'] . '</option>';
    }

    echo App\Views\FormRenderer::renderFormField('
                <select name="employerId" class="input">
                    <option value="-">Izaberite poslodavca</option>
                    ' . $employersHtml. '
                </select>
            ', $errors['employerId'] ?? null);
    echo App\Views\FormRenderer::renderFormField('<input type="text" id="jobName" name="jobName" placeholder="Naziv oglasa" class="input">', $errors['jobName'] ?? null);
    echo App\Views\FormRenderer::renderFormField('<textarea id="description" name="description" placeholder="Opis oglasa" class="input"></textarea>', $errors['description'] ?? null);

    $fieldsHtml = '<option value="-">Izaberite polje rada</option>';
    foreach ($fields as $field) {
        $fieldsHtml .= '<option value="' . $field . '">' . ucfirst($field) . '</option>';
    }

    echo App\Views\FormRenderer::renderFormField('<select name="field" class="input">' . $fieldsHtml . '</select>', $errors['field'] ?? null);
    echo App\Views\FormRenderer::renderFormField('<input type="number" id="startSalary" name="startSalary" placeholder="Pocetna plata" class="input">', $errors['startSalary'] ?? null);
    echo App\Views\FormRenderer::renderFormField('
                <select name="shifts" class="input">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            ', $errors['shifts'] ?? null);

    $locationsHtml = '<option value="-">Izaberite grad</option>';
    foreach ($locations as $location) {
        $locationsHtml .= '<option value="' . $location . '">' . $location . '</option>';
    }

    echo App\Views\FormRenderer::renderFormField('<select class="input" name="location">' . $locationsHtml . '</select>', $errors['location'] ?? null);
    echo App\Views\FormRenderer::renderFormField('
                <input type="checkbox" id="flexibleHours" name="flexibleHours">
                <label for="flexibleHours">Klizno radno vreme</label>    
            ', $errors['flexibleHours'] ?? null);
    echo App\Views\FormRenderer::renderFormField('
                <input type="checkbox" id="workFromHome" name="workFromHome">
                <label for="workFromHome">Rad od kuce</label>    
            ', $errors['workFromHome'] ?? null);
    ?>
    <input type="submit" value="Kreirajte oglas" class="form-btn">
</form>