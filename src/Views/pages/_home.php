<h1 class="user-hello-msg">hello, <?= $user['firstName'] ?></h1>

<?php

echo $jobRenderer->renderJobs('Oglasi za vas', $jobs, $favourites);
echo $jobRenderer->renderJobs('Vase prijave', $jobsAppliedTo, $favourites);
echo $jobRenderer->renderJobs('Vasi omiljeni oglasi', $favouriteJobs, $favourites);

?>