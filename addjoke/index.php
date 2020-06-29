<?php
if(get_magic_quotes_gpc()){
  $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
  while(list($key, $val) = each($process)){
    foreach($val as $k => $v){
      unset($process[$key][$k]);
      if(is_array($v)){
        $process[$key][stripslashes($k)]=$v;
        $process[] = &$process[$key][stripslashes($k)];
      }
      else{
        $process[$key][stripslashes($k)] = stripslashes($v);
      }
    }
  }
  unset($process);
}
if(isset($_GET['addjoke'])){
  include 'form.html.php';
  exit();
}
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
if(isset($_POST['joketext'])){
  try{
    $sql = 'INSERT INTO joke SET 
      joketext = :joketext,
      jokedate = CURDATE()';
      $s = $pdo->prepare($sql);
      $s->bindValue(':joketext', $_POST['joketext']);
      $s->execute();
  }
  catch(PDOException $e){
    $error='Ошибка при добалении шутки: '. $e->getMESSage();
    include 'error.html.php';
    exit();
  }
  header('Location: .');
  exit();
}
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