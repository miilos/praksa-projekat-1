<form method="post" action="/executeUpdate/<?= $job['jobId'] ?>" class="form" autocomplete="off">
    <h1 class="form-title">Azuriraj oglas</h1>

    <?php
    $employersHtml = '';
    foreach ($employers as $employer) {
        if ($employer['employerId'] === $job['employerId']) {
            $employersHtml .= '<option value="' . $employer['employerId'] . '" selected>' . $employer['employerName'] . '</option>';
        }
        else {
            $employersHtml .= '<option value="' . $employer['employerId'] . '">' . $employer['employerName'] . '</option>';
        }
    }

    echo \App\Views\FormRenderer::renderFormField('
                <select name="employerId" class="input" disabled>
                    <option value="-">Izaberite poslodavca</option>
                    ' . $employersHtml . '
                </select>
            ');
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="jobName" name="jobName" placeholder="Naziv oglasa" class="input" value="' . $job['jobName'] . '">');
    echo \App\Views\FormRenderer::renderFormField('<textarea id="description" name="description" placeholder="Opis oglasa" class="input">' . $job['description'] . '</textarea>');
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="field" name="field" placeholder="Polje rada" class="input" value="' . $job['field'] . '" disabled>');
    echo \App\Views\FormRenderer::renderFormField('<input type="number" id="startSalary" name="startSalary" placeholder="Pocetna plata" class="input" value="' . $job['startSalary'] . '">');
    echo \App\Views\FormRenderer::renderFormField('
                <select name="shifts" class="input">
                    <option ' . ($job['shifts'] === 1 ? 'selected' : '') . ' value="1">1</option>
                    <option ' . ($job['shifts'] === 2 ? 'selected' : '') . ' value="2">2</option>
                    <option ' . ($job['shifts'] === 3 ? 'selected' : '') . ' value="3">3</option>
                </select>
            ');
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="location" name="location" placeholder="Lokacija" class="input" value="' . $job['location'] . '">');
    echo \App\Views\FormRenderer::renderFormField('
                <input type="checkbox" id="flexibleHours" name="flexibleHours" ' . ($job['flexibleHours'] ? 'checked' : '') . '>
                <label for="flexibleHours">Klizno radno vreme</label>
            ');
    echo \App\Views\FormRenderer::renderFormField('
                <input type="checkbox" id="workFromHome" name="workFromHome" ' . ($job['workFromHome'] ? 'checked' : '') . '>
                <label for="workFromHome">Rad od kuce</label>    
            ');
    ?>
    <input type="submit" value="Azurirajte oglas" class="form-btn">
</form>