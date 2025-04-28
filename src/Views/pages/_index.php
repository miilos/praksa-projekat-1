<form method="post" action="/" class="form search" autocomplete="off">
    <h1 class="search-title">Filteri za pretragu</h1>

    <input type="text" id="jobName" name="jobName" class="input" placeholder="Naziv oglasa" >
    <input type="text" id="location" name="location" class="input" placeholder="Lokacija">

    <input type="checkbox" id="flexibleHours" name="flexibleHours">
    <label for="flexibleHours">Klizno radno vreme</label>

    <input type="checkbox" id="workFromHome" name="workFromHome">
    <label for="workFromHome">Rad od kuce</label>

    <input type="submit" id="submit" name="submit-filters" class="form-btn" value="Primeni filtere">
</form>

<?= $jobRenderer->renderJobs($title, $jobs, $favourites); ?>