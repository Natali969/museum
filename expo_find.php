<?php
session_start();

if(empty($_SESSION['login'])) {
  header("Refresh:0; url=login.php");
  die();
}

if (!empty($_POST['s'])) {
  if (isset($_POST['del_search'])) {
    $_SESSION['is_find'] = 0;
  } else {
    // мы ищем что-то или выводим всю таблицу
    $_SESSION['is_find'] = 1;
    //что ищем
    $_SESSION['find'] = $_POST['s'];
  }
}
header("Refresh:0; url=watch_db.php");
?>