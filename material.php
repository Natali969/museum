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
// кнопка "Сохранить материал" изначально добавляет материал
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Работа c материалами</title>
<link rel="stylesheet" href="material.css">
</head>
<body>

<div id="container" >
  <div id="wrapper">
  <div id="redact" class="animate form">
            <form method='POST'> 
                <h1>Работа с таблицей "Материалы"</h1> 
                <p>
                  <label for="status_expo">Наименование материала</label>
                  <input name="name_mat" type="text" placeholder="например, кожа" value="<?php
                  echo $_SESSION['upd_mat'] ?? '';
                  unset($_SESSION['upd_mat']);?>"> 
                </p>
                <p>
                    <button name='save'>Сохранить материал</button>
                </p>
                <p class="message">  
                    Вернуться к 
                    <a href="watch_db.php" class="to_register"> редактированию базы данных экспонатов </a>
                </p>
                <p class="message">
                    Проверить
                    <a href="admin/admin.php" class="to_register">зарегистрированных пользователей</a>
                </p>
                <p class="message">  
                    Или можете посмотреть
                    <a href="show_book.php" class="to_register"> фотографии журнала экспонатов </a>
                </p>
            </form>
        </div>
    <!-- добавить форму из файла index.php  -->
</div>

<?php
if(isset($_POST['save']) and $_POST['name_mat'] != '') {
  if ($_SESSION['mat_upd']==0){ //добавляем материал
    $add_material = $_POST['name_mat'];
    $sql_add = "INSERT INTO materials (name_mat) VALUES ('".$add_material."')";
    $add_mat = mysqli_query($connection, $sql_add);
    header("Refresh:0");
  } else { //изменяем существующий материал
    $upd_material = $_POST['name_mat'];
    $sql_update = "UPDATE materials
    SET name_mat = '".$upd_material."'
    WHERE id_mat = ".$_SESSION['id_upd_mat'].";";
    $upd_mat = mysqli_query($connection, $sql_update);
    $_SESSION['mat_upd'] = 0;
  }
}
?>

<div class="menu">
    <table class="table">
      <thead>
        <tr>
        <th scope="col">ID</th>
          <th scope="col">Материал</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
      <?php
        // получить все экспонаты из таблицы
        // наверху будут находиться только что зарегистрировавшиеся пользователи
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
            ?>
            <tr>
              <th scope="row"><?= $i?></th>
              <td><?= $application[1] ?></td>
              <?php echo "<form action='isset_update_material.php' method='POST'><td><button name='del[$but]' class='add'>Изменить</button></td>
              <input type='hidden' name='hhh' value='$but'></form>"?>
              <?php $but++;?>
              <?php echo "<form action='isset_del_material.php' method='POST'><td><button name='del[$but]' class='del'>Удалить</button></td>
              <input type='hidden' name='hhh' value='$but'></form>"?>              
          </tr>
          <?php
          }
      } else { ?>
        <th scope="row">-</th>
        <td colspan = "2">Нет материалов</td>
        <?php
      }
    ?>   
      </tbody>
    </table>
</div>
</body>
</html>