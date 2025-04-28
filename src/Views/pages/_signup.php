<form method="post" action="/signup" class="form" autocomplete="off">
    <h1 class="form-title">Sign up</h1>

    <?php
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="firstName" name="firstName" placeholder="Ime" class="input">', $errors['firstName'] ?? null);
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="lastName" name="lastName" placeholder="Prezime" class="input">', $errors['lastName'] ?? null);
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="email" name="email" placeholder="Email adresa" class="input">', $errors['email'] ?? null);
    echo \App\Views\FormRenderer::renderFormField('<input type="password" id="password" name="password" placeholder="Password" class="input">', $errors['password'] ?? null);
    echo \App\Views\FormRenderer::renderFormField('<input type="password" id="passwordConfirm" name="passwordConfirm" placeholder="Confirm password" class="input">', $errors['passwordConfirm'] ?? null);
    echo \App\Views\FormRenderer::renderFormField(
        '<select name="field" class="input">
                            <option value="-">Izaberite polje rada...</option>
                            <option value="it">IT</option>
                            <option value="prodaja">Prodaja</option>
                            <option value="pravo">Pravo</option>
                            <option value="menadzment">Menadzment</option>
                        </select>'
        , $errors['field'] ?? null);
    ?>
    <input type="submit" value="Sign up" class="form-btn">
</form>