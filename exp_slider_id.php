<!-- <script src="scripts/sim-slider.js"></script> -->
<?php
// echo $_POST['slider'];
// echo $_POST;
// echo $_POST['item'];
// $_SESSION['img_ex_del'] = $_POST['slider'];
if (isset($_POST['u_name']))
{
    echo $_POST['u_name'];
    $_SESSION['id_im_exp'] = $_POST['u_name'];
    echo $_SESSION['id_im_exp'];
}

else
{
    echo "<script type='text/javascript'>";
    echo "document.write('<form method=\'post\'>');";
    echo "document.write('<p>Ваше имя:<br />');";
    echo "document.write('<input type=\'text\' name=\'u_name\' value = \'' + slideIndex + '\'</p>');";
    echo "document.write('<input type=\'submit\' />');";
    echo "document.write('</form>');";
    echo "</script>";
    exit();
}
?>