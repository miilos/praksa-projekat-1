<?php

// no using autoload because this file is meant to be included in other files that already required autoload
use App\Managers\SessionManager;

$user = SessionManager::getSessionData('user');

?>

<nav class="nav-container">
    <ul class="nav-list nav-list__menu">
        <li class="nav-item">
            <a href="/" class="nav-item-link">Svi oglasi</a>
        </li>
        <?php if ($user && $user['role'] === 'admin') {
            echo '
                <li class="nav-item">
                    <a href="/job/create" class="nav-item-link">Kreiranje oglasa</a>
                </li>
                <li class="nav-item">
                    <a href="/adminSelection" class="nav-item-link">Azuriranje oglasa</a>
                </li>
                <li class="nav-item">
                    <a href="/adminSelection" class="nav-item-link">Brisanje oglasa</a>
                </li>
            ';
        }
        ?>
    </ul>

    <ul class="nav-list nav-list__users">
        <?php
            if($user) {
                echo '
                    <li class="nav-item">
                        <a href="/home" class="nav-item-link">' . $user['firstName'] . '</a>
                    </li>
                ';
            }
            else {
                echo '
                    <li class="nav-item">
                        <a href="/login" class="nav-item-link nav-item-link--login">Log in</a>
                    </li>
                    <li class="nav-item">
                        <a href="/signup" class="nav-item-link nav-item-link--signup">Sign up</a>
                    </li>
                ';
            }
        ?>
    </ul>
</nav>