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


$sql = "SELECT id_img
FROM images
WHERE id_exp = ".$_SESSION['img_ex_del']."
ORDER BY id_img DESC";
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
    $id = $appls[$n-1];
    echo $id;
    $sql_del = "DELETE FROM images
    WHERE id_img = ".$id.";";
    $del_mat = mysqli_query($connection, $sql_del);

    $_SESSION['view_slides']=2;
    header("Refresh:0; url=exponat_photo.php");
    
  }
}
mysqli_close($connection);

?>