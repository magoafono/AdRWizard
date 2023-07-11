<?php
session_start();

include 'config.php';

//IL CHECK NON FUNZIONA!!
if(!session_is_registered($_SESSION['user'])){
  header( "Location: login.php" );
 } 

$debug = 0;
if ($debug==1){
  
  echo "id = ($id)<br/>";
 }
$id = $_POST['id'];

$con = mysql_connect($dbhost, $dbuser, $dbpwd);
if (!$con)
  {
    die('Could not connect: ' . mysql_error());
  }

mysql_select_db($dbname, $con);


$delete = "DELETE FROM assegni WHERE id = ". $id . ";";
if ($debug == 1) 
    echo $delete."<br/>";
$result = mysql_query($delete);

if (!$result) {
  //  die('Invalid query: ' . mysql_error());
 } else {
  echo "<html>\n";
  echo "<head>\n";
  echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
  echo "<meta http-equiv=\"refresh\" content=\"3; URL=lista.php\">\n";
  echo "<title>Richiesta Bando di concorso (Assegno di Ricerca)</title>\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"login.css\" media=\"all\">\n";
  echo "</head>\n";
  echo "<body id=\"main_body\">\n";
  echo "<h1>Richiesta eliminata</h1>";
  echo "La richiesta con identificativo (".$id.") e' stata eliminata.";

 }

mysql_close($con);

if (!$result) {
  echo "<form name=\"savedata\" id=\"savedata1\" method=\"post\" action=errore.php>";
  echo "<input type=\"hidden\" id=\"err_id\" name=\"errore\" value=".$result.">";
  echo "</form>";
  
 } else {
  echo "<form name=\"savedata\" id=\"savedata1\" method=\"post\" action=lista.php>";
  echo "<input type=\"hidden\" id=\"user_id\" name=\"utente\" value=\"nome_utente\">";
  echo "</form>";

 }

?>
