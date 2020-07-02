<?php include_once $_SERVER['DOCUMENT_ROOT'] .
    '/includes/helpers.inc.php';?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <title>Каталог товаров</title>
    <style>
    table {
      border-collapse: collapse;
    }
    td, th {
      border: 1px solid black;
    }
    </style>
  </head>
  <body>
    <p>Ваша корзина содержит <?php echo count($_SESSION['cart']); ?> товаров.</p>
    <p><a href="?cart">Посмотреть корзину</a></p>
    <table border="1">
      <thead>
        <tr>
          <th>Описание товара</th>
          <th>Цена</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $item): ?>
          <tr>
            <td><?php htmlout($item['desc']);?></td>
            <td>
              $<?php echo number_format($item['price'], 2); ?>
            </td>
            <td>
              <form action="" method="post">
                <div>
                  <input type="hidden" name="id" value="<?php htmlout($item['id']);?>">
                  <input type="submit" name="action" value="Купить">
                </div>
              </form>
            </td>
          </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <p>Все цены указаны в тугриках.</p>
  </body>
</html>