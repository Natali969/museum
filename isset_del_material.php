<?php
session_start();

if(empty($_SESSION['login'])) {
  header("Refresh:0; url=login.php");
  die();
}

$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'museum';
$connection = mysqli_connect($server, $username, $password, $dbname);
if($connection->connect_error) {
  die("Ошибка соединения".$connection->connect_error);
}


$sql = "SELECT * FROM materials ORDER BY name_mat";
$applications = mysqli_query($connection, $sql);
$all_applications = mysqli_fetch_all($applications);
$i = 0;
$but = 0;
$appls = array(); // define array students

if ($applications->num_rows > 0) {
  foreach ($all_applications as $application) {
    $appls[$i] = $application[0];
    $i++;
    $but++;
    $but++;
  }
}

if ($applications->num_rows > 0) {
  if(isset($_POST['del'])) {
    // $n - номер кнопки
    $n = $_POST['hhh'];
    $n = (int)$n;
    // эта функция будет добавлять пользователя в список авторизованных и
  // удалять его из списка зарегистрированных
    if ($n % 2 == 0) {// эта функция будет удалять пользователя из списка зарегистрированных
      $id_zap = $n/2;
   
      $id = $appls[$id_zap-1];

      $sql_set = "SET FOREIGN_KEY_CHECKS=0;";
      $set_key = mysqli_query($connection, $sql_set);

      $sql_del = "DELETE FROM materials
      WHERE id_mat = ".$id.";";
      $del_mat = mysqli_query($connection, $sql_del);

      $sql_set = "SET FOREIGN_KEY_CHECKS=1;";
      $set_key = mysqli_query($connection, $sql_set);
  
      header("Refresh:0; url=material.php");
    }
    
  }
}
mysqli_close($connection);

?>