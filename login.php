<?php
session_start();

if(!empty($_SESSION['login'])) {
    header("Refresh:0; url=watch_db.php");
    die();
}

$norm_aut = 1;
// $norm_reg = 1;

if (!empty($_POST['username']) && !empty($_POST['password'])) {
    $ins_user = $_POST['username'];
    $password = $_POST['password'];
    $error = '';

    $server = '127.0.0.1';
    $username = 'root';
    $pass = '';
    $dbname = 'users';
    $connection = mysqli_connect($server, $username, $pass, $dbname);
    // проверим, существует ли пользователь в списке авторизованных
    $sql_sel = "SELECT * FROM autorize
    WHERE (login_auto = '".$ins_user."' OR email_auto = '".$ins_user."') AND passw_auto = '".$password."';";
    $aut_user = mysqli_query($connection, $sql_sel);

    $_SESSION['login'] = $ins_user;

    $all_users = mysqli_fetch_all($aut_user);
    $i = 0;
    $but = 0;
    $appls = array(); // define array students

    $sql_reg = "SELECT * FROM applications
    WHERE (login = '".$ins_user."' OR email = '".$ins_user."') AND password = '".$password."';";
    $reg_user = mysqli_query($connection, $sql_reg);
    $reg_users = mysqli_fetch_all($reg_user);

    // если бд вернула непустой запрос, значит,
    // такой пользователь существует
    if ($aut_user->num_rows > 0) {
        // foreach ($all_users as $us) {
            header("Refresh:0; url=watch_db.php");
            $_SESSION['login'] = $ins_user;
            $_SESSION['mat_upd'] = 0;
            die();
        //   }
        } else if ($reg_user->num_rows > 0) { 
            $_SESSION['login'] = '';
            echo "Пользователь зарегистрирован";
            header("Refresh:0; url=wait.php");
            die();
        } else {
            $error = 'Неверный логин или пароль';
            $norm_aut = 0;
        }
}
?>
<!doctype html>
<head>
    <meta charset="utf-8">
    <title>Вход в систему</title>
    <link rel="stylesheet" href="work-style.css">
    </head>
<body>
<link rel="stylesheet" href="style.css">

  <div id="container_demo" >
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>
    <div id="wrapper">
        <div id="login" class="animate form">
            <form  action="" method="POST"> 
                <h1>Вход</h1> 
                <p> 
                    <label for="username" class="uname" data-icon="u" > Ваш e-mail или логин</label>
                    <input id="username" name="username" required="required" type="text" placeholder="sitehere или sitehere.ru@my.com"/>
                    <script type="text/javascript">
                    document.getElementById('username').value = "<?php if (!empty($_SESSION['login'])) {
                        echo $_SESSION['login'];
                    }?>";
                    </script>
                </p>
                <p> 
                    <label for="password" class="youpasswd" data-icon="p"> Ваш пароль </label>
                    <input id="password" name="password" required="required" type="password" placeholder="например 123456" /> 
                </p>
                <p class="keeplogin">
                    <?php if($norm_aut==0) {?>
                        <div class='error'>
                            <?php
                            echo $error;
                            $norm_aut = 1;
                            ?>
                            </div>
                            <?php
                        }?>
                </p>
                <button>Войти</button>
                </p>
                <p class="message">
                    Ещё не зарегистрированы ?
                    <a href="#toregister" class="to_register">Присоединяйтесь</a>
                </p>
                <p class="message">  
                    Или можете посмотреть
                    <a href="show_book.php" class="to_register"> фотографии журнала экспонатов </a>
                </p>
            </form>
        </div>
 
        <div id="register" class="animate form">
            <form action = 'register.php' name='test' method="POST"> 
                <h1> Регистрация </h1> 
                <p> 
                    <label for="usernamesignup" class="uname" data-icon="u">Ваш логин</label>
                    <input id="usernamesignup" name="usernamesignup" required="required" type="text" placeholder="myname1" />
                </p>
                <p> 
                    <label for="emailsignup" class="youmail" data-icon="e" > Ваш e-mail</label>
                    <input id="emailsignup" name="emailsignup" required="required" type="email" placeholder="sitehere.ru@my.com"/> 
                </p>
                <!-- <form name='test' method='POST' action=''> -->
                <div onload='onload()'>
                    <p> 
                        <label for="passwordsignup" class="youpasswd" data-icon="p">Ваш пароль </label>
                        <input id="passwordsignup" name="passwordsignup" required="required" type="password" placeholder="123456"/>
                    </p>
                    <p> 
                        <label for="passwordsignup_confirm" class="youpasswd" data-icon="p">Подтвердите ваш пароль </label>
                        <input id="passwordsignup_confirm" name="passwordsignup_confirm" required="required" type="password" placeholder="123456"/>
                    </p>
                    <p class="keeplogin">
                    <?php 
                    if (isset($_SESSION['norm_reg'])) {
                        if($_SESSION['norm_reg']==0) {?>
                            <div class='error'>
                                <?php
                                $err = 'Введённые пароли не совпадают';
                                echo $err;
                                $_SESSION['norm_reg'] = 1;
                                ?>
                            </div>
                            <?php
                            }
                        }?>
                    </p>
                    <div id='nameValidation' class='validation-image'></div>
                </div>
                <!-- </form> -->
                <script type="text/javascript">
                    window.oninput = function () {
                        console.log('func');
                        var validationElement = document.getElementById('nameValidation');
                        var passw1 = document.forms.test.passwordsignup.value;
                        // var passw1 = document.getElementById('passwordsignup').value;
                        var passw2 = document.getElementById('passwordsignup_confirm').value;
                        validationElement.style.display = 'none';
                        validationElement.className = 'validation-image';
                        if (passw2) {
                            validationElement.style.display = 'inline-block';
                            validationElement.className +=
                            (passw1 == passw2 ? 'validation-success' : 'validation-error');
                        }
                    }
                </script>
                <button name='reg'>Регистрация</button>
                </p>
                <p class="message">  
                    Уже зарегистрированы ?
                    <a href="#tologin" class="to_register"> Войдите на сайт </a>
                </p>
                <p class="message">  
                    Или можете посмотреть
                    <a href="show_book.php" class="to_register"> фотографии журнала экспонатов </a>
                </p>
            </form>
        </div>						
    </div>
</div>

</body>
</html>
  
  
