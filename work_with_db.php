<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Работа в БД</title>
<link rel="stylesheet" href="work-style.css">
</head>
<body>

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
$charset = 'utf_8';
$connection = new mysqli($server, $username, $password, $dbname);
if($connection->connect_error) {
die("Ошибка соединения".$connection->connect_error);
}
?>

<div id="container" >
  <div id="wrapper">
  <div id="redact" class="animate form">
            <form action='isset_add_db.php' method="POST"> 
                <h1>Работа с БД экспонатов</h1> 
                <p> 
                  <label for="date_give">Дата поступления</label>
                  <input type="date" class="form-control" id="date" name="date" placeholder="Дата" value="<?php
                  echo $_SESSION['date_exp'] ?? '';
                  unset($_SESSION['date_exp']);?>">
                </p>
                <p>
                  <label for="name_expo">Наименование экспоната и его краткое описание</label>
                  <textarea class="form-control" id="name_expo" name = "name_expo" rows="4" name="text"><?php
                  echo $_SESSION['name_exp'] ?? '';
                  unset($_SESSION['name_exp']);?></textarea>
                </p>
                <p>
                <label for="material">Материал</label>
                <select class="form-control" name="Category" id="category">
                  <?php
                  $sql = "SELECT * FROM materials ORDER BY name_mat";
                  $all_materials = mysqli_query($connection, $sql);
                  // use a while loop to fetch data
                  // from the $all_categories variable
                  // and individually display as an option
                  while ($material = mysqli_fetch_array(
                    $all_materials,MYSQLI_ASSOC)):;
                    if ($_SESSION['exp_upd']==0) {
                      if ($material['name_mat']=="Нет данных") {
                        ?>
                        <option value="<?php echo $material["id_mat"];
                        // The value we usually set is the primary key
                        ?>" selected="selected">
                        <?php
                        echo $material["name_mat"];
                        ?>
                        </option>
                        <?php
                        // To show the category name to the user
                      } else {
                        ?>
                        <option value="<?php echo $material["id_mat"];
                        // The value we usually set is the primary key
                        ?>">
                        <?php
                        echo $material["name_mat"];
                        // To show the category name to the user
                        ?>
                        </option>
                      <?php
                      }
                    } else {
                      if ($material['id_mat']==$_SESSION['mat_id_exp']) {
                        ?>
                        <option value="<?php echo $material['id_mat'];
                        // The value we usually set is the primary key
                        ?>" selected="selected">
                        <?php
                        echo $material["name_mat"];
                        unset($_SESSION['mat_id_exp']);
                        ?>
                        </option>
                        <?php
                      } else {
                        ?>
                        <option value="<?php echo $material["id_mat"];
                        // The value we usually set is the primary key
                        ?>">
                        <?php
                        echo $material["name_mat"];
                        // To show the category name to the user
                        ?>
                        </option>
                      <?php
                      }
                    }
                    endwhile;
                    // While loop must be terminated
                    ?>
                </select>
                <a class="button" href="http://localhost/museum/material.php" target="_blank">Добавить новый</a>
                </p>
                <p>
                  <label for="status_expo">Состояние предмета</label>
                  <input id="status" name="status" type="text" placeholder="например, порван" value="<?php
                  echo $_SESSION['status_exp'] ?? '';
                  unset($_SESSION['status_exp']);?>"/> 
                </p>
                <p>
                  <label for="from_expo">Откуда поступил</label>
                  <input id="from" name="from" type="text" placeholder="например, от ветерана Осиповой Е. К." value="<?php
                  echo $_SESSION['from_exp'] ?? '';
                  unset($_SESSION['from_exp']);?>"/> 
                </p>
                <p>
                <button name="add">Сохранить экспонат</button>
                </p>
                <p class="message">
                    Изменить
                    <a href="watch_db.php" class="to_register">базу данных экспонатов</a>
                </p>
                <p class="message">
                    Перейти к
                    <a href="exponat_photo.php" class="to_register">редактированию фото экспонатов</a>
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
</div>
</body>
</html>