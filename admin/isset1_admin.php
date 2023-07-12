<?php
session_start();

if (empty($_SESSION['login'])) {
    header("Refresh:0; url=login.php");
    die();
}

$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'users';
// $charset = 'utf8mb4_general_ci';
$connection = mysqli_connect($server, $username, $password, $dbname);

if($connection->connect_error) {
  die("Ошибка соединения".$connection->connect_error);
}
$sql = "SELECT id, login, email FROM applications ORDER BY id";
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
    if ($n % 2 == 1) {
      $id_zap = ceil($n/2);
      $id = $appls[$id_zap-1];
  
      $sql_add = "INSERT INTO autorize (login_auto, email_auto, passw_auto)
      SELECT a.login, a.email, a.password
      FROM applications a
      WHERE id = ".$id.";";
      $add_user = mysqli_query($connection, $sql_add);
      $sql_del = "DELETE FROM applications
      WHERE id = ".$id.";";
      $add_user = mysqli_query($connection, $sql_del);
  
      header("Refresh:0; url=admin.php");
      // $all_applications = mysqli_query($connection, $sql_add);
    }
  }
}
// mysqli_close($connection);
?>