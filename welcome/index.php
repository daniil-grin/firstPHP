<?php
if(!isset($_REQUEST['firstname'])){
  include 'form.html.php';
}
else{
  $firstname = $_REQUEST['firstname'];
  $lastname = $_REQUEST['lastname'];
  if($firstname=='Вадим' and $lastname == 'Егоров'){
    $output = 'Добро подаловать, администратор!';
  }
  else{
  echo 'Добро пожаловать на наш веб-сайт, '.
  htmlspecialchars($firstname,ENT_QUOTES, 'UTF-8').' '.
  htmlspecialchars($lastname,ENT_QUOTES, 'UTF-8').'!';
  }
  include 'welcome.html.php';
}
?>