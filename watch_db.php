<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Просмотр БД</title>
<link rel="stylesheet" href="work-style.css">
</head>
<body>

<?php
session_start();

if (empty($_SESSION['login'])) {
    header("Refresh:0; url=login.php");
    die();
}

$count = 5;
$page = $_GET['page'] ?? 0;
$page_count = 0;

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

<div id="container" ></div>

<div class="menu">
  <div class='links'>
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
  </div>
    <p></p>
    <p>
    <form action="expo_find.php" name='find' id='find' method="POST">
      <button name='del_search' id='del_search'></button>
      <input name="s" id='s' placeholder="Найти экспонат..." type="search">
      <script type="text/javascript">
      document.getElementById('s').value = "<?php
      if (isset($_SESSION['is_find'])) {
        if (!empty($_SESSION['find']) && $_SESSION['is_find']==1) {
          echo $_SESSION['find'];
      }
        }?>";
        </script>
      <button type="submit" name='search' id='search'></button>
      <a class="button_add" name="add_expo" href="http://localhost/museum/work_with_db.php" target="_blank">Добавить экспонат</a>
    </form>
    </p>
    <p></p>
    <table class="table" id='paged'>
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Наименование и краткое описание предмета</th>
          <th scope="col">Материал</th>
          <th scope="col">Состояние</th>
          <th scope="col">Откуда поступил</th>
          <th scope="col">Дата поступления</th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </thead>
      <tbody>
      <?php
        // получить все экспонаты из таблицы
        // наверху будут находиться только что зарегистрировавшиеся пользователи
        // если мы просто выводим таблицу
        if (isset($_SESSION['is_find'])) {
          if ($_SESSION['is_find'] == 0) {
            $sql = "SELECT id, name, m.name_mat, status, from_what, e.date
            FROM exponats e
            INNER JOIN materials m ON m.id_mat = e.mat_id
            ORDER BY id";
          }
          else {
            $sql = "SELECT id, name, m.name_mat, status, from_what, e.date
            FROM exponats e
            INNER JOIN materials m ON m.id_mat = e.mat_id
            WHERE name LIKE '%".$_SESSION['find']."%'
            ORDER BY name";
            // $_SESSION['is_find'] = 0;
          }
        } else {
          $sql = "SELECT id, name, m.name_mat, status, from_what, e.date
          FROM exponats e
          INNER JOIN materials m ON m.id_mat = e.mat_id
          ORDER BY id";
        }
        $exponats = mysqli_query($connection, $sql);
        $all_exponats = mysqli_fetch_all($exponats);
        $i = 0;
        $but = 0;
        $appls = array(); // define array students
        $page_count = 0;

        if ($exponats->num_rows > 0) {
          // количество страниц
          $page_count = ceil(($exponats->num_rows)/$count);
          for ($p=$page*$count; $p<($page+1)*$count; $p++) {
            $_SESSION['page_expo'] = $page;
            if (isset($all_exponats[$p])) {
              $appls[$i] = $all_exponats[$p][0];
              $i++;
              $but++;
              ?>
              <tr>
                <th scope="row"><?= $i+$page*$count?></th>
                <td><?= $all_exponats[$p][1] ?></td>
                <td><?= $all_exponats[$p][2] ?></td>
                <td><?= $all_exponats[$p][3] ?></td>
                <td><?= $all_exponats[$p][4] ?></td>
                <td><?= $all_exponats[$p][5] ?></td>
                <?php echo "<form action='expo_change.php' method='POST'><td><button name='del[$but]' class='add'>Изменить</button></td>
                <input type='hidden' name='hhh' value='$but'></form>"?>
                <?php $but++;?>
                <?php echo "<form action='expo_del.php' method='POST'><td><button name='del[$but]' class='del'>Удалить</button></td>
                <input type='hidden' name='hhh' value='$but'></form>"?>
            </tr>
            <?php
            }
          }
      } else { ?>
        <th scope="row">-</th>
        <td colspan = "7">Нет доступных экспонатов</td>
        <?php
      }
    ?>   
      </tbody>
      <tfoot>
        <tr>
            <!-- Здесь появится блок ссылок постраничной навигации //-->
            <td colspan="8">
            <?php
            if ($page_count > 0) { ?>
              <a href='?page=<?=0?>' tabindex="0">
              <button id='page_start' tabindex="0" selected='selected'>Назад</button>
            </a>
              <?php
            }
            if ($page_count < 7 && $page_count > 0) {
              for ($t = 0; $t < $page_count; $t++) :?>
                <a href='?page=<?=$t?>' tabindex="0">
                <button id='page_button' tabindex="0" selected='selected'><?= $t+1?></button>
                </a>
                <?php endfor;?>
                <a href='?page=<?=$page_count-1?>' tabindex="0">
                <button id='page_end' tabindex="0" selected='selected'>Вперёд</button>
                </a>
                <p class="message">  
                  Страница <?=$page+1?>
                </p>
                <?php
            }
            // текущая страница - 3 в списке выводимых страниц
            if ($page_count > 6 && $page>=0 && $page<2) {
              for ($t = 0; $t < 6; $t++) :?>
                <a href='?page=<?=$t?>' tabindex="0">
                <button id='page_button' tabindex="0" selected='selected'><?= $t+1?></button>
                </a>
                <?php endfor;?>
                <a href='?page=<?=$page_count-1?>' tabindex="0">
                <button id='page_end' tabindex="0" selected='selected'>Вперёд</button>
                </a>
                <p class="message">  
                  Страница <?=$page+1?>
                </p>
                <?php
            }
            // текущая страница - 3 в списке выводимых страниц
            if ($page_count > 6 && $page<$page_count-3 && $page>1) {
              for ($t = $page-2; $t < $page+4; $t++) :?>
                <a href='?page=<?=$t?>' tabindex="0">
                <button id='page_button' tabindex="0" selected='selected'><?= $t+1?></button>
                </a>
                <?php endfor;?>
                <a href='?page=<?=$page_count-1?>' tabindex="0">
                <button id='page_end' tabindex="0" selected='selected'>Вперёд</button>
                </a>
                <p class="message">  
                  Страница <?=$page+1?>
                </p>
                <?php
            }
            // если текущая страница одна из 3 последних,
            // выводятся последние 6
            if ($page_count > 6 && $page>=$page_count-3 && $page<=$page_count) {
              for ($t = $page_count-6; $t < $page_count; $t++) :?>
                <a href='?page=<?=$t?>' tabindex="0">
                <button id='page_button' tabindex="0" selected='selected'><?= $t+1?></button>
                </a>
                <?php endfor;?>
                <a href='?page=<?=$page_count-1?>' tabindex="0">
                <button id='page_end' tabindex="0" selected='selected'>Вперёд</button>
                </a>
                <p class="message">  
                  Страница <?=$page+1?>
                </p>
                <?php
            }
            ?>
            </td>
        </tr>
    </tfoot>
    </table>
</div>

</body>
</html>