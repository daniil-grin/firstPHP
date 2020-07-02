<?php

if (isset($_COOKIE['visits'])) {
    $_COOKIE['visits'] = 0;
}
$visits = $_COOKIE['visits']++;
setcookie('visits', $visits, time() + 3600 * 24 * 365);

include 'welcome.html.php';
