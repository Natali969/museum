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
    if ($n % 2 == 1) {// эта функция будет удалять пользователя из списка зарегистрированных
      $id_zap = ceil($n/2);
      $id = $appls[$id_zap-1];

      $_SESSION['id_upd_mat'] = $id;
      $sql_get_data = "SELECT * FROM materials
      WHERE id_mat = ".$id.";";
      $upd_mat_from_sql = mysqli_query($connection, $sql_get_data);
      $upd_mat = mysqli_fetch_row($upd_mat_from_sql);
      $_SESSION['upd_mat'] = $upd_mat[1];
      $_SESSION['mat_upd'] = 1;
      }
      header("Refresh:0; url=material.php");
    }
    
  }
mysqli_close($connection);

?>