<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>
<body>
  <p><a href="?addjoke">Добавте собственную шутку</a></p>
  <p>Все шутки, которые есть в базе данных:</p>
  <?php foreach ($jokes as $joke): ?>
    <blockquote>
      <p>
        <?php echo htmlspecialchars($joke, ENT_QUOTES, "UTF-8");
        ?>
      </p>
    </blockquote>
  <?php endforeach; ?>
</body>
</html>