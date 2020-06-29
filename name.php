<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <?php
  $firstname = $_GET['firstname'];
  $lastname = $_GET['lastname'];
  echo 'Добро пожаловать на наш веб-сайт, '.htmlspecialchars($firstname,ENT_QUOTES, 'UTF-8').' '.htmlspecialchars($lastname,ENT_QUOTES, 'UTF-8').'!';
  ?>
</body>
</html>