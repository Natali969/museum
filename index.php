<!-- <!— подключение к БД музея —> -->
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

// if($connection->set_charset($charset)) {
// echo "Ошибка кодировки UTF-8";
// }

// получить все экспонаты из таблицы
$sql = "SELECT * FROM `exponats` ORDER BY name";
$all_categories = mysqli_query($connection, $sql);

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Загрузка картинки в БД</title>
</head>
<body>
<!— добавление картинки в таблицу с картинками —>
<form action="index.php" method="post" enctype="multipart/form-data">
    <p>Загрузить картинку</p>
    <!-- выпадающий список из экспонатов музея, с одним из которых соотносится картинка -->
    <label>Выберите экспонат музея</label>
    <select name="Category">
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
    <p></p>
    <input type="file" name="img_upload"><input type="submit" name="upload" value="Загрузить">
    <?php
    if(isset($_POST['upload'])){
        $id_exp = mysqli_real_escape_string($connection,$_POST["Category"]);
        $ID_EXP = (int)$id_exp;
        if(!empty($_FILES['img_upload']['tmp_name'])) {
            $id_exp = mysqli_real_escape_string($connection,$_POST["Category"]);
            $img = addslashes(file_get_contents($_FILES['img_upload']['tmp_name']));
            $connection->query("INSERT INTO images(id_exp, img) VALUES ('$ID_EXP','$img')");
            unset($img);
        }
    }
    ?>
</form>
<?php
$query = $connection->query("SELECT * FROM images ORDER BY id_img DESC");
while($row = $query->fetch_assoc()) {
$show_img = base64_encode($row['img']);?>
<img src="data:image/jpeg;base64, <?php echo $show_img ?>" alt="">
<?php } ?>
</body>
</html>