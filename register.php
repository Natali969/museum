<?php
session_start();
$server = '127.0.0.1';
$username = 'root';
$password = '';
$dbname = 'users';
$connection = mysqli_connect($server, $username, $password, $dbname);

if($connection->connect_error) {
  die("Ошибка соединения".$connection->connect_error);
}

if(isset($_POST['reg']) and $_POST['usernamesignup'] != '' and
$_POST['emailsignup'] != '' and $_POST['passwordsignup'] != '' and
$_POST['passwordsignup_confirm'] != '') {
    if ($_POST['passwordsignup'] == $_POST['passwordsignup_confirm']) {
        $log = $_POST['usernamesignup'];
        $email = $_POST['emailsignup'];
        $pass = $_POST['passwordsignup'];
        

        $sql_add = "INSERT INTO applications (login, email, password)
        VALUES ('".$log."', '".$email."', '".$pass."')";
        $add_reg = mysqli_query($connection, $sql_add);
        header("Refresh:0; url=wait.php");
    } else {
        $_SESSION['norm_reg'] = 0;
        header("Refresh:0; url=login.php#toregister");
    }
    
}
mysqli_close($connection);
?>