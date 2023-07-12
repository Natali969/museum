<?php
session_start();
echo $_SESSION['exp_upd'];
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

if(isset($_POST['add']) and $_POST['name_expo'] != '') {
  // запись новых данных из формы
  $date = $_POST['date'];
  $name = $_POST['name_expo'];
  $mat = $_POST['Category'];
  echo $mat;
  $mat = (int)$mat;
  $status = $_POST['status'];
  $from = $_POST['from'];
  if ($_SESSION['exp_upd']==0) { //добавляем экспонат 
    $sql_add = "INSERT INTO exponats (name, mat_id, status, from_what, date)
    VALUES ('".$name."', '".$mat."', '".$status."', '".$from."', '".$date."')";
    $add_exp = mysqli_query($connection, $sql_add);
    header("Refresh:0; url=work_with_db.php");
  } else { //изменяем существующий экспонат
    $sql_update = "UPDATE exponats
    SET name = '".$name."', mat_id = " .$mat. ", status = '".$status."', from_what = '".$from."', date = '".$date."'
    WHERE id = ".$_SESSION['id_upd_exp'].";";
    $upd_exp = mysqli_query($connection, $sql_update);
    $_SESSION['exp_upd'] = 0;
    header("Refresh:0; url=watch_db.php");
  }
}


mysqli_close($connection);
?>