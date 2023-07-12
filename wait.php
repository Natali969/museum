<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ожидание разрешения администратора</title>
        <link rel="stylesheet" href="style.css">
    </head>
<body>
  <div id="container_demo" >
    <a class="hiddenanchor" id="toregister"></a>
    <a class="hiddenanchor" id="tologin"></a>
    <div id="wrapper">
        <div id="login" class="animate form">
            <form  action="mysuperscript.php" autocomplete="on"> 
                <h1>Отлично! Вы зарегистрированы!</h1> 
                <p> 
                    <label for="username" class="uname" data-icon="u" > Для доступа к базе данных необходимо дождаться разрешения администратора</label>
                </p>
                <p class="message">  
                    А пока можете посмотреть
                    <a href="show_book.php" class="to_register"> фотографии журнала экспонатов </a>
                </p>
            </form>
        </div>					
    </div>
</div>
</body>