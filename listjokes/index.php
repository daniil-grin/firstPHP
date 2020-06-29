<?php 
// Подключение
try{
  $pdo = new PDO('mysql:host=localhost;dbname=int_joke', 'jokesuser', '123');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
  $output = 'Невозможно подключиться к серверу баз данных: '.$e->getMessage();
  include 'error.html.php';
  exit();
}
// Выборка данных
try{
  $sql='SELECT joketext from joke';
  $result=$pdo->query($sql);
}
catch(PDOException $e){
  $error='Ошибка при извлечении шуток: '. $e->getMessage();
  include 'error.html.php';
  exit();
}
while ($row = $result->fetch()) {
  $jokes[] = $row['joketext'];
}
include 'jokes.html.php';
?>