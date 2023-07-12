<!-- администратор даёт права на изменение БД зарегистрированным пользователям -->
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

?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Работа с пользователями</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<form method="POST">
<div class="menu">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Логин</th>
          <th scope="col">Email</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
      <?php
        // получить все экспонаты из таблицы
        // наверху будут находиться только что зарегистрировавшиеся пользователи
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
            ?>
            <tr>
              <th scope="row"><?= $i?></th>
              <td><?= $application[1] ?></td>
              <td><?= $application[2] ?></td>
              <?php echo "<form action='isset1_admin.php' method='POST'><td><button name='del[$but]' class='add'>Добавить</button></td>
              <input type='hidden' name='hhh' value='$but'></form>"?>
              <?php $but++;?>
              <?php echo "<form action='isset2_admin.php' method='POST'><td><button name='del[$but]' class='del'>Удалить</button></td>
              <input type='hidden' name='hhh' value='$but'></form>"?>
          </tr>
          <?php
          }
      } else { ?>
        <th scope="row">-</th>
        <td colspan = "2">Нет текущих заявок</td>
        <?php
      }
        // $application[0] - id зарегистрированного пользователя в таблице
    ?>   
      </tbody>
    </table>
    <p class="message">  
      К редактированию
      <a href="http://localhost/museum/watch_db.php" class="to_register"> базы данных экспонатов </a>
    </p>
    <p class="message">
      Перейти к
      <a href="http://localhost/museum/exponat_photo.php" class="to_register">редактированию фото экспонатов</a>
    </p>
    <p class="message">  
      Или можете посмотреть
      <a href="http://localhost/museum/show_book.php" class="to_register"> фотографии журнала экспонатов </a>
    </p>
</div>
</form>
</body>
</html>