<div class="job-header">
    <?php if ($user): ?>
        <button class="favourites-btn" data-user="<?= $user['userId'] ?>" data-job="<?= $job['jobId'] ?>">
                <span class="material-symbols-outlined">
                    <?= $isFavourite ? 'favorite' : 'heart_plus' ?>
                </span>
        </button>
    <?php endif; ?>

    <h1 class="job-header-title"><?= $job['jobName'] ?></h1>
    <h3 class="job-header-employer"><?= $job['employerName'] ?></h3>
    <div class="job-header-info">
        <div>
                <span class="material-symbols-outlined">
                    location_on
                </span>
            <?= $job['location'] ?>
        </div>
        <div>
                <span class="material-symbols-outlined">
                    schedule
                </span>
            <?= date('j.n.Y.', strtotime($job['createdAt'])) ?>
        </div>
        <div>
                <span class="material-symbols-outlined">
                    update
                </span>
            <?= $job['shifts'] === 1 ? $job['shifts'] . ' smena' : $job['shifts'] . ' smene' ?>
        </div>
    </div>
    <div class="job-header-extra-info">
        <div class="job-details-detail job-details-detail--field">
            <?= $job['field'] ?>
        </div>
        <?php
        if ($job['workFromHome']) {
            echo '
                        <div class="job-details-detail">
                            <span class="material-symbols-outlined">
                                home
                            </span>
                            Rad od kuce
                        </div>
                    ';
        }

        if ($job['flexibleHours']) {
            echo '
                        <div class="job-details-detail">
                            <span class="material-symbols-outlined">
                                schedule
                            </span>
                            Klizno radno vreme
                        </div>
                    ';
        }
        ?>
    </div>
</div>

<div class="job-info">
    <div class="job-description">
        <h1 class="job-description-title">Opis posla:</h1>
        <p class="job-description-desc"><?= $job['description'] ?></p>
    </div>

    <div class="employer-info">
        <h1 class="employer-info-title">O poslodavcu:</h1>
        <p class="employer-info-desc"><?= $job['employerDescription'] ?></p>
        <p class="employer-info-based-in"><b>Baziran u: </b><?= $job['basedIn'] ?></p>
    </div>
</div>

<div class="job-comments">
    <h1 class="job-comments-title">Komentari:</h1>

    <div class="comments">
        <?php
        if (!$comments) {
            echo '<h3 class="no-comments">Jos nema komentara za ovaj oglas</h3>';
        }
        else {
            foreach ($comments as $comment) {
                echo '
                            <div class="comment">
                                <div class="comment-header">
                                    <h3 class="comment-user">' . $comment['firstName'] . '</h3>
                                    <span class="comment-header--gray">&bull;</span>
                                    <p class="comment-created-at comment-header--gray">' . date('j.n.Y.', strtotime($comment['createdAt'])) . '</p>
                                </div>
                                <p class="comment-text">' . $comment['text'] . '</p>
                            </div>
                        ';
            }
        }
        ?>
    </div>

    <?php
    if ($user) {
        echo '
                    <textarea class="input comment-input" rows="10" placeholder="Ostavite komentar"></textarea>
                    <button class="btn btn--primary comment-btn" data-username="' . $user['firstName'] . '" data-user="' . $user['userId'] . '" data-job="' . $job['jobId'] . '">Postavi</button>
                ';
    }
    ?>
</div>

<div class="job-application">
    <?php
    if ($userApplication) {
        echo '
                    <h1 class="job-application-title">Prijavili ste se na ovaj oglas ' . date('j.n.Y.', strtotime($userApplication['submittedAt'])) . '</h1>
                ';
    }
    else {
        echo '
                    <h1 class="job-application-title">Prijavi se!</h1>
                    <a href="/apply?jobId=' . $job['jobId'] . '" class="btn btn--secondary">Posalji prijavu</a>
                ';
    }
    ?>
</div>