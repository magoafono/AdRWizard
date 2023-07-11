<?php
include 'config.php';

session_start();

//IL CHECK NON FUNZIONA!!
if(!session_is_registered($_SESSION['user'])){
  header( "Location: login.php" );
 } 

include 'config.php';

$user = $_POST['user'];
$pwd = $_POST['pwd'];
$oldpwd  = $_POST['oldpwd'];
$newpwd  = $_POST['newpwd'];
$newpwd2 = $_POST['newpwd2'];

$debug = 0;
if ($debug==1){
  
  echo "user    = $user<br/>";
  echo "pwd     = $pwd<br>";
  echo "oldpwd  = $oldpwd<br/>";
  echo "newpwd  = $newpwd<br/>";
  echo "newpwd2 = $newpwd2<br/>";
  echo "strcmp($pwd, $oldpwd) = ".strcmp($pwd, $oldpwd)." <br>";
 }


if ( strcmp($pwd, $oldpwd)) {
  echo "<form name=\"savedata\" id=\"changepwd1\" method=\"post\" action=change-pwd.php>";
  echo "<input type=\"hidden\" id=\"err_id\" name=\"errore\" value=\"La vecchia password e' errata! Password non cambiata!\">";
  echo "<input type=\"hidden\" id=\"user\" name=\"user\" value=\"$user\">";
  echo "</form>";
  
 }elseif ( strcmp($newpwd, $newpwd2)) {

  echo "<form name=\"savedata\" id=\"changepwd1\" method=\"post\" action=change-pwd.php>";
  echo "<input type=\"hidden\" id=\"err_id\" name=\"errore\" value=\"La nuova password non coincide con la conferma! Password non cambiata!\">";
  echo "<input type=\"hidden\" id=\"user\" name=\"user\" value=\"$user\">";
  echo "</form>";
   
 } else {
  
  $con = mysql_connect($dbhost, $dbuser, $dbpwd);
  if (!$con)
    {
      die('Could not connect: ' . mysql_error());
    }
  
  mysql_select_db($dbname, $con);
  
  $update = "UPDATE users SET userPass = '$newpwd' where userName = '$user';";
  
  if ($debug == 1) {
    echo $update."<br/>";
  }
  $result = mysql_query($update,$con);
  
  if (!$result) {
    echo ('Invalid query 1: ' . mysql_error(). ' '.($update));
  }
  
  if (!$result) {
    echo "<form name=\"savedata\" id=\"changepwd1\" method=\"post\" action=lista.php>";
    echo "<input type=\"hidden\" id=\"err_id\" name=\"errore\" value=".$result.">";
    echo "<input type=\"hidden\" id=\"pwd\" name=\"pwd\" value=\"not changed\">";
    echo "</form>";
    
  } else {
    
    echo "<form name=\"savedata\" id=\"changepwd1\" method=\"post\" action=lista.php>";
    echo "<input type=\"hidden\" id=\"user_id\" name=\"utente\" value=\"nome_utente\">";
    echo "<input type=\"hidden\" id=\"pwd\" name=\"pwd\" value=\"changed\">";
    echo "</form>";
    
  }
  
  mysql_close($con);
 }
?>


<script type="text/javascript">
  setTimeout('document.savedata.submit()',1);
</script>
