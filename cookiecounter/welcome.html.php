
<?php
include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/helpers.inc.php';
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Счетчик cookies</title>
  </head>
  <body>
    <p>
      <?php
if ($visits > 1) {
    echo "Номер данного посещения: $visits.";
} else {
    // Первое посещение
    echo 'Добро пожаловать на мой веб-сайт! Кликните здесь, чтобы узнать больше!';
}
?>
    </p>
  </body>
</html>