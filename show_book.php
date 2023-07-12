<!-- Указание типа текущего документа: -->
<!DOCTYPE html>
<html>

  <!-- Содержимое тега <head> не отображается на странице, помогает в работе с данными и хранит информацию для поисковых систем и браузеров: -->
  <head>
    <meta charset="utf-8" />
    <title>Книга учёта экспонатов музея</title>
	<link rel="stylesheet" type="text/css" href="slider-style.css">
  </head>

  <!-- Здесь содержится весь отображаемый контент: -->
  <body>
	<?php
$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'museum';
$charset = 'utf_8';
$connection = new mysqli($server, $username, $password, $dbname);

if($connection->connect_error) {
die("Ошибка соединения".$connection->connect_error);
}

// получить все экспонаты из таблицы
$sql = "SELECT * FROM `exponats` ORDER BY name";
$all_categories = mysqli_query($connection, $sql);

?>
<!-- <form action="exponat_photo.php" method="post" enctype="multipart/form-data"> -->
<!-- <form action="index.php" method="post" enctype="multipart/form-data"> -->
<!-- выпадающий список из экспонатов музея, с одним из которых соотносится картинка -->
<!-- <form action='isset_add_str_exp.php' method='POST' enctype='multipart/form-data'> -->
<?php
session_start();
// echo $_SESSION['login'];
// echo $_SESSION;
if (!empty($_SESSION['login'])) {
  // echo 'not empty';
  echo "<div class='menu'>
  <form action='isset_add_list.php' method='POST' enctype='multipart/form-data'>
  <label class='input-file'>
    <input type='file' name='img_upload'>
    <span>Выберите файл</span>
  </label>
  <script src='https://snipp.ru/cdn/jquery/2.1.1/jquery.min.js'></script>
  <script language='JavaScript'>
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
  <p class='message'>
    Изменить
    <a href='watch_db.php' class='to_register'>базу данных экспонатов</a>
  </p>
  <p class='message'>
    Перейти к
    <a href='exponat_photo.php' class='to_register'>редактированию фото экспонатов</a>
  </p>
  <p class='message'>
   Проверить
   <a href='admin/admin.php' class='to_register'>зарегистрированных пользователей</a>
  </p>
</div>";
}?>
</form>
<div class="slider">
  <?php
  $i = 0;
  $query = $connection->query("SELECT *
  FROM list_exponats
  ORDER BY id DESC");
  if ($query->num_rows > 0) {
    $i = 0;
    while($row = $query->fetch_assoc()) {
      $i++;
      $show_img = base64_encode($row['str_img']);?>
      <div class="item">
        <img name="<?=$num[$i]?>" src="data:image/jpeg;base64, <?php echo $show_img ?>" alt="">
        <?php if (!empty($_SESSION['login'])) {
          echo "<form action='isset_del_list.php' method='POST'><td><button name='del[$i]' class='del'>Удалить фотографию</button></td>
          <input type='hidden' name='hhh' value='$i'></form>";
        }?>
      </div>
      <?php
      } ?>
      <script src="scripts/sim-slider.js"></script>
      <a class="previous" onclick="previousSlide()">&#10094;</a>
      <a class="next" onclick="nextSlide()">&#10095;</a>
    </div>
    <?php 
    } else {?>
      <label name='no_photo' id='no_photo'>Фотографии страниц журнала не загружены</label>
    <?php 
    } ?>
</div>
</body>
</html>