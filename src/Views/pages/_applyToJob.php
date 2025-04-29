<form method="post" action="/apply/<?= $jobId ?>" class="form" autocomplete="off">
    <h1>Vasi podaci za prijavu na oglas:</h1>
    <input type="hidden" class="input" value="<?= $user['userId'] ?>" name="userId">
    <input type="hidden" class="input" value="<?= $jobId ?>" name="jobId">

    <div class="input-container">
        <input type="text" id="firstName" name="firstName" value="<?= $user['firstName'] ?>" class="input" readonly>
    </div>

    <div class="input-container">
        <input type="text" id="lastName" name="lastName" value="<?= $user['lastName'] ?>" class="input" readonly>
    </div>

    <div class="input-container">
        <input type="text" id="email" name="email" value="<?= $user['email'] ?>" class="input" readonly>
    </div>

    <input type="submit" name="submit" class="form-btn" value="Prijavite se">
</form>