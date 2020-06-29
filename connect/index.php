<?php 
try{
  $pdo = new PDO('mysql:host=localhost;dbname=int_joke', 'jokesuser', '123');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->exec('SET NAMES "utf8"');
}
catch(PDOException $e){
  $output = 'Невозможно подключиться к серверу баз данных: '.$e;
  include 'output.html.php';
  exit();
}
$output = 'Соединение с базой данных установлено';
include 'output.html.php';
