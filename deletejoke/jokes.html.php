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
    <form action="?deletejoke" method="post">
      <blockquote>
        <p>
          <?php echo htmlspecialchars($joke['text'], ENT_QUOTES, "UTF-8");?>
          <input type="hidden" name="id" value="<?php echo $joke['id']; ?>">
          <input type="submit" value="Удалить">
        </p>
      </blockquote>
    </form>
  <?php endforeach;?>
</body>
</html>