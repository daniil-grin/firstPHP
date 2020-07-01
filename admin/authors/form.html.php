<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/helpers.inc.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title><?=htmlout($pageTitle);?></title>
</head>
<body>
  <h1><?=htmlout($pageTitle);?></h1>
  <form action="?<?=htmlout($action);?>" method="POST">
  <div>
    <label for="name">Имя: <input type="text" name="name" id="name" value="<?php htmlout($name); ?>"></label>
  </div>
  <div>
    <label for="email">Email: <input type="email" name="email" id="email" value="<?php htmlout($email); ?>"></label>
  </div>
  <div>
    <input type="hidden" name="id" value="<?php htmlout($id);?>">
    <input type="submit" value="<?php htmlout($button);?>">
  </div>
  </form>
</body>
</html>