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
// echo !empty($_FILES['img_upload']['tmp_name']);
if(isset($_POST['upload']) and !empty($_FILES['img_upload']['tmp_name'])) {
  // echo "img download";
  $id_exp = mysqli_real_escape_string($connection,$_SESSION['img_exp_category']);
  $ID_EXP = (int)$id_exp;
  $id_exp = mysqli_real_escape_string($connection,$_SESSION['img_exp_category']);
  $img = addslashes(file_get_contents($_FILES['img_upload']['tmp_name']));
  // echo "INSERT INTO images(id_exp, img) VALUES ('$ID_EXP','$img')";
  $connection->query("INSERT INTO images(id_exp, img) VALUES ('".$ID_EXP."','".$img."')");
  unset($img);
  $_SESSION['view_slides']=2;
}
header("Refresh:0; url=exponat_photo.php");
?>