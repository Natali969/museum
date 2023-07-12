<?php
session_start();

if(empty($_SESSION['login'])) {
    header("Refresh:0; url=login.php");
    die();
}

// echo $_SESSION['img_exp'];

$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'museum';
$connection = mysqli_connect($server, $username, $password, $dbname);
if($connection->connect_error) {
  die("Ошибка соединения".$connection->connect_error);
}
// echo !empty($_FILES['img_upload']['tmp_name']);
if(isset($_POST['upload']) and !empty($_FILES['img_upload']['tmp_name'])) {
  // $id_exp = mysqli_real_escape_string($connection,$_SESSION['img_exp']);
  // $ID_EXP = (int)$id_exp;
  // $id_exp = mysqli_real_escape_string($connection,$_SESSION['img_exp']);
  $img = addslashes(file_get_contents($_FILES['img_upload']['tmp_name']));
  $connection->query("INSERT INTO list_exponats(str_img) VALUES ('$img')");
  unset($img);
  
  header("Refresh:0; url=show_book.php");
}
?>