<?php
session_start();

if(empty($_SESSION['login'])) {
    header("Refresh:0; url=login.php");
    die();
}
?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Загрузка картинки в БД</title>
<link rel="stylesheet" type="text/css" href="slider-style.css">
</head>
<body>
<!-- добавление картинки в таблицу с картинками -->
<?php
$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'museum';
$charset = 'utf_8';
$connection = new mysqli($server, $username, $password, $dbname);
$_SESSION['view_slides'] = 1;
if($connection->connect_error) {
die("Ошибка соединения".$connection->connect_error);
}

if ($_SESSION['view_slides']==1) {
  $c = 0;
  $sql = "SELECT * FROM `exponats` ORDER BY name";
  $applications = mysqli_query($connection, $sql);
  $all_applications = mysqli_fetch_all($applications);
  if ($applications->num_rows > 0) {
    foreach ($all_applications as $application) {
      if ($c==0) {
        $_SESSION['img_exp_category'] = $application[0];
      }
      $c++;
    }
  }
}


// получить все экспонаты из таблицы
$sql = "SELECT * FROM `exponats` ORDER BY name";
$all_categories = mysqli_query($connection, $sql);
?>
<form method='POST' enctype="multipart/form-data">
  <div class="menu">
    <label>Выберите экспонат музея</label>
    <p></p>
    <select name="Category" id="category">
        <?php
        // use a while loop to fetch data
        // from the $all_categories variable
        // and individually display as an option
        while ($category = mysqli_fetch_array(
            $all_categories,MYSQLI_ASSOC)):;
            ?>
            <option value="<?php echo $category["id"];
            // The value we usually set is the primary key
            ?>">
            <?php
            echo $category["name"]
            // echo $category["id"]," ", $category["name"];
            // To show the category name to the user
            ?>
            </option>
            <?php
            endwhile;
            // While loop must be terminated
            ?>
    </select>
    <script type="text/javascript">
    document.getElementById('category').value = "<?php if (!empty($_POST['Category'])) {
      echo $_POST['Category'];
    } else {
      echo $_SESSION['img_exp_category'];
    }?>"; //$_POST['Category']
    </script>
    <p></p>
    <button name='show'>Показать фотографии</button>
</form>
    <p>Загрузить картинку</p>
    <p></p>

    <form action='isset_add_im_exp.php' method='POST' enctype="multipart/form-data">
    <label class="input-file">
      <input type="file" name="img_upload">
      <span>Выберите файл</span>
    </label>
    <script src="https://snipp.ru/cdn/jquery/2.1.1/jquery.min.js"></script>
    <script language="JavaScript">
    $('.input-file input[type=file]').on('change', function(){
	  let file = this.files[0];
    name_img = file.name;
    if (name_img.length > 22) {
      first = name_img.substring(0,15);
      last = file.name.substring(name_img.length - 7);
      result = first + '...' + last;
      $(this).next().html(result);
    } else {
      $(this).next().html(file.name);
    }
    })
    </script>
    <p></p>
    <button name='upload'>Загрузить новую</button>
    </form>

    <p></p>
    <p class="message">
      Изменить
      <a href="watch_db.php" class="to_register">базу данных экспонатов</a>
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

  <div class="slider" name='slider' id='slider'>

  <?php
  $i = 0;
  if (isset($_POST['show']) or $_SESSION['view_slides']>0){
    $_SESSION['view_slides'] = 0;
    if (!empty($_POST['Category'])) {
      $exp = $_POST['Category'];
      $_SESSION['img_exp_category'] = $_POST['Category'];
      $_SESSION['img_ex_del'] = $exp;
      $_SESSION['img_exp'] = $exp;
    } else {
      $exp = $_SESSION['img_exp_category'];
    }
    $query = $connection->query("SELECT id_img, img
    FROM images
    WHERE id_exp = ".$exp."
    ORDER BY id_img DESC");
    $i = 0;
    if ($query->num_rows > 0) {
          while($row = $query->fetch_assoc()) {
            $i++;
            $show_img = base64_encode($row['img']);?>
            <div class="item" value=<?=$i?>>
            <img src="data:image/jpeg;base64, <?php echo $show_img ?>" alt="">
            <?php echo "<form action='isset_del_im_exp.php' method='POST'><td><button name='del[$i]' class='del'>Удалить фотографию</button></td>
            <input type='hidden' name='hhh' value='$i'></form>"?>
            </div>
            <?php
          } ?>
          <script src="scripts/sim-slider.js"></script>
          <a class="previous" onclick="previousSlide()">&#10094;</a>
          <a class="next" onclick="nextSlide()">&#10095;</a>
        </div>
        <?php 
        } else {
          ?>
          <label name='no_photo' id='no_photo'>Фотографий этого экспоната пока нет</label>
          <?php
        }
      }
      ?>
      </div>
      <table class="table" id='paged'>
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Наименование и краткое описание предмета</th>
          <th scope="col">Материал</th>
          <th scope="col">Состояние</th>
          <th scope="col">Откуда поступил</th>
          <th scope="col">Дата поступления</th>
        </tr>
      </thead>
      <tbody>
      <?php
        // получить все экспонаты из таблицы
        // наверху будут находиться только что зарегистрировавшиеся пользователи
        // если мы просто выводим таблицу
        $sql = "SELECT id, name, m.name_mat, status, from_what, e.date
        FROM exponats e
        INNER JOIN materials m ON m.id_mat = e.mat_id
        WHERE e.id = ".$_SESSION['img_exp_category']."
        ORDER BY id";
        $exponats = mysqli_query($connection, $sql);
        $all_exponats = mysqli_fetch_all($exponats);
        $i = 0;
        $but = 0;
        $appls = array(); // define array students
        $page_count = 0;

        if ($exponats->num_rows > 0) {
          // количество страниц
          for ($p=0; $p<2; $p++) {
            // $_SESSION['page_expo'] = $page;
            if (isset($all_exponats[$p])) {
              $appls[$i] = $all_exponats[$p][0];
              $i++;
              $but++;
              ?>
              <tr>
                <th scope="row"><?= $i?></th>
                <td><?= $all_exponats[$p][1] ?></td>
                <td><?= $all_exponats[$p][2] ?></td>
                <td><?= $all_exponats[$p][3] ?></td>
                <td><?= $all_exponats[$p][4] ?></td>
                <td><?= $all_exponats[$p][5] ?></td>
            </tr>
            <?php
            }
          }
      }
    ?>   
      </tbody>
    </table>
</body>
</html>