<form method="post" action="/login" class="form" autocomplete="off">
    <h1 class="form-title">Log in</h1>

    <?php
    echo \App\Views\FormRenderer::renderFormField('<input type="text" id="email" name="email" placeholder="Email adresa" class="input">', $errors['email'] ?? null);
    echo \App\Views\FormRenderer::renderFormField('<input type="password" id="password" name="password" placeholder="Password" class="input">', $errors['password'] ?? null);
    ?>

    <input type="submit" value="Log in" class="form-btn">
</form>