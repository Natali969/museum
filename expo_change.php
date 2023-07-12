<?php
session_start();

if(empty($_SESSION['login'])) {
    header("Refresh:0; url=login.php");
    die();
}

$count = 5;
$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'museum';
$connection = mysqli_connect($server, $username, $password, $dbname);
if($connection->connect_error) {
  die("Ошибка соединения".$connection->connect_error);
}


$sql = "SELECT id, name, m.name_mat, status, from_what, e.date FROM exponats e
INNER JOIN materials m ON m.id_mat = e.mat_id
ORDER BY id";
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
      // $n = $n/2;

      $n = ceil($n/2);
      $id_zap = $_SESSION['page_expo']*$count +$n;
      $id = $appls[$id_zap-1];

      $_SESSION['id_upd_exp'] = $id;
      //id, name, mat_id, status, from_what, date
      $sql_get_data = "SELECT * FROM exponats
      WHERE id = ".$id.";";
      echo $sql_get_data;
      $upd_exp_from_sql = mysqli_query($connection, $sql_get_data);
      $upd_mat = mysqli_fetch_row($upd_exp_from_sql);

      $_SESSION['name_exp'] = $upd_mat[1];
      $_SESSION['mat_id_exp'] = $upd_mat[2];
      $_SESSION['status_exp'] = $upd_mat[3];
      $_SESSION['from_exp'] = $upd_mat[4];
      $_SESSION['date_exp'] = $upd_mat[5];
      $_SESSION['exp_upd'] = 1;
      }
      header("Refresh:0; url=work_with_db.php");
    }
    
  }
mysqli_close($connection);

?>