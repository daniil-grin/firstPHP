<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/helpers.inc.php';?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Управление шутками: результаты поиска</title>
  </head>
  <body>
    <h1>Результаты поиска</h1>
    <?php if (isset($jokes)): ?>
      <table>
        <tr><th>Текст шутки</th><th>Действия</th></tr>
        <?php foreach ($jokes as $joke): ?>
        <tr>
          <td><?php markdownhtml($joke['text']);?></td>
          <td>
            <form action="?" method="post">
              <div>
                <input type="hidden" name="id" value="<?php htmlout($joke['id']);?>">
                <input type="submit" name="action" value="Редактировать">
                <input type="submit" name="action" value="Удалить">
              </div>
            </form>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
    <?php endif;?>
    <p><a href="?">Новый поиск</a></p>
    <p><a href="..">Вернуться на главную</a></p>
    <?php include '../logout.inc.html.php'; ?>
  </body>
</html>