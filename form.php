<?php
  //ini_set("display_errors","1");
  //ERROR_REPORTING(E_ALL);
  //start the session
session_start();
//echo "(".$_SESSION['user'].")";
// Report simple running errors


include 'config.php';
include 'default_value.php';
include 'common.php';

//check to make sure the session variable is registered
if(!session_is_registered($_SESSION['user'])){
  header( "Location: login.php" );
 }

// Create a simple array.
//$arr_hh = array("8","9","10","11","12","13","14","15","16","17","18");
$arr_hh = array("8:00","8:30","9:00","9:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30");
//$arr_mm = array("00", "15", "30", "45");
//print_r ($_POST);

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>Richiesta Bando di concorso (Assegno di Ricerca)</title>
  <link rel="stylesheet" type="text/css" href="view.css" media="all"/>
  <script type="text/javascript" src="view.js"></script>
  <script type="text/javascript" src="calendar.js"></script>
  <?php getTinyMCE(); ?>
</head>


<body id="main_body">
  
  <img id="top" src="top.png" alt=""/>
  <div id="form_container">
  <?php


if(!($db = @mysql_connect($dbhost, $dbuser, $dbpwd))) //connect to database
  die("Couldn't connect to the database.");
if(!@mysql_select_db($dbname, $db)) //select database
  die("Database doesn't exist!");

$sede = -1;

/*print('<pre>');
print_r("(".$_POST['role'].")");
print('</pre>');
*/
$role = $_POST['role'];

if (isset($_POST['id'])) {

  //recupero dal db la ennupla con id
  $id = $_POST['id'];
  
  $sql = 'SELECT * ';
  $sql .= 'FROM assegni where id = '.$id;
  //$sql .= 'ORDER BY id DESC LIMIT 0, 5';
  
  $result = @mysql_query($sql, $db) or die("Query failed : " . mysql_error());
  
  // For each result that we got from the Database
  
  $row = mysql_fetch_assoc($result); //il risultato ha 1 sola ennupla
  
  $nome_richiedente = $row["nome_richiedente"];

  $data_richiesta = $row["data_richiesta"];
  if ($data_richiesta == "") {
    $data_richiesta = date("j-m-Y");
  } 

  $id_assegno = $row["id_assegno"];
  
  $id_tipo_assegno = $row["id_tipo_assegno"];

  $data_servizio = $row["data_servizio"];
  list($presa_servizio_DD, $presa_servizio_MM,$presa_servizio_YYYY) =
    split("-", $data_servizio, 3);
  
  $membri_commissione = $row["membri_commissione"];
  $date_colloquio = $row["date_colloquio"];
  
  $data_colloquio_1 = $row["data_colloquio_1"];
  list($data_colloquio_1_DD, $data_colloquio_1_MM,$data_colloquio_1_YYYY) =
    split("-", $data_colloquio_1, 3);
  
  $data_colloquio_2 = $row["data_colloquio_2"];
  list($data_colloquio_2_DD, $data_colloquio_2_MM,$data_colloquio_2_YYYY) =
    split("-", $data_colloquio_2, 3);
  
  $data_colloquio_3 = $row["data_colloquio_3"];
  list($data_colloquio_3_DD, $data_colloquio_3_MM,$data_colloquio_3_YYYY) =
    split("-", $data_colloquio_3, 3);
  
  
  $data_scadenza_bando = $row["data_scadenza_bando"];
  list($scadenza_bando_DD, $scadenza_bando_MM,$scadenza_bando_YYYY) =
    split("-", $data_scadenza_bando, 3);
  
  $ora_colloquio_1 = $row["ora_colloquio_1"];
//  console_log("ora_colloquio_1: " . $ora_colloquio_1);
  $ora_colloquio_2 = $row["ora_colloquio_2"];
  $ora_colloquio_3 = $row["ora_colloquio_3"];
  
  /*
   $ora_colloquio_1 = $row["ora_colloquio_1"];
   list($hh_colloquio_1, $mm_colloquio_1) =
   split(":", $ora_colloquio_1, 2);
   
   $ora_colloquio_2 = $row["ora_colloquio_2"];
   list($hh_colloquio_2, $mm_colloquio_2) =
   split(":", $ora_colloquio_2, 2);
   
   $ora_colloquio_3 = $row["ora_colloquio_3"];
   list($hh_colloquio_3, $mm_colloquio_3) =
   split(":", $ora_colloquio_3, 2);
  */
  
  $ambito_ricerca = $row["ambito_ricerca"];
  $commessa_modulo = $row["commessa_modulo"];
  $tema               = $row["tema"];
  $responsabile = $row["responsabile"];
  $durata = (int)$row["durata"];
  
  $importo            = $row["importo"];
  list($importo_cifre,$importo_lettere) = split (" ",$importo,2);
  
  $requisiti_lauree = htmlspecialchars_decode($row["requisiti_lauree"]);
  $requisiti_esperienze = htmlspecialchars_decode($row["requisiti_esperienze"]);

  $status = $row["status"];
  $commento = $row["commento"];
  
  $sede = $row["id_sede"];
  $id_template = $row["id_template"];
  $fondi_ordinari = $row ["fondi_ordinari"];

  //PROVVEDIMENTO
  $n_provvedimento = $row["n_provvedimento"];
  $data_provvedimento = $row["data_provvedimento"];
  list($data_provvedimento_DD, $data_provvedimento_MM,$data_provvedimento_YYYY) =
    split("-", $data_provvedimento, 3);
  
  //  print_r ($fondi_ordinari);

  $id_tipo_form = $row["id_tipo_form"];

  mysql_free_result($result);
  
 } else {
  //NUOVA RICHIESTA
  $id = -1;
  $status = "";
  $id_tipo_assegno = 0;
  $presa_servizio_DD = "";
  $presa_servizio_MM = "";
  $presa_servizio_YYYY = "";
  $membri_commissione = "";
  $date_colloquio = 0;
  $data_colloquio_1_DD = "";
  $data_colloquio_1_MM = "";
  $data_colloquio_1_YYYY = "";
  $ora_colloquio_1 = "";
  $data_colloquio_2_DD = "";
  $data_colloquio_2_MM = "";
  $data_colloquio_2_YYYY = "";
  $ora_colloquio_2 = "";
  $data_colloquio_3_DD = "";
  $data_colloquio_3_MM = "";
  $data_colloquio_3_YYYY = "";
  $ora_colloquio_3 = "";
  $arr_mm = "";
  $scadenza_bando_DD = "";
  $scadenza_bando_MM = "";
  $scadenza_bando_YYYY = "";
  $ambito_ricerca = "";
  $commessa_modulo = "";
  $tema = "";
  $durata = 0;
  $importo_cifre = "";
  $importo_lettere = "";
  $requisiti_lauree = "";
  $requisiti_esperienze = "";
  $responsabile = "";

  $data_richiesta = date("j-m-Y");

  //  echo "nome_richiedente = ". $_POST['nome_richiedente'];
  $nome_richiedente = $_POST['nome_richiedente'];
  $fondi_ordinari = "0";

  //recupero dell'ultimo id template che e' il piu' recente
  $sql = 'SELECT max(id_template) as last_id ';
  $sql .= 'FROM template ';
  $result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error() . ", query is:" . $sql);
  $row = mysql_fetch_assoc($result); //il risultato ha 1 sola ennupla
  
  $id_template = $row['last_id'] ;
  
  mysql_free_result($result);

  //recupero dell'ultimo id form che e' il piu' recente
  $sql = 'SELECT max(id_form) as last_id ';
  $sql .= 'FROM form ';
  $result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error() . ", query is:" . $sql);
  $row = mysql_fetch_assoc($result); //il risultato ha 1 sola ennupla
  
  $id_tipo_form = $row['last_id'] ;
  
  mysql_free_result($result);
 }

//RECUPERO DA DB DEL RICHIEDENTE
$sql = 'SELECT * ';
$sql .= 'FROM users where userName = \''.$nome_richiedente.'\'';
$result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error());
$row = mysql_fetch_assoc($result); //il risultato ha 1 sola ennupla
$nome_richiedente_esteso = $row ['nome'] ." ". $row['cognome'];
mysql_free_result($result);

//RECUPERO DA DB DELLE COMMESSE
$sql = 'SELECT * ';
$sql .= 'FROM commesse ';
$sql .= 'ORDER BY id';
$result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error());
while ( $row = mysql_fetch_assoc($result)){
  $arr_commesse[] = $row;
 }
mysql_free_result($result);

//RECUPERO DA DB DEI TIPI DI ASSEGNO
$sql = 'SELECT * ';
$sql .= 'FROM tipo_assegni ';
$sql .= 'ORDER BY id_tipo_assegno';
$result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error());
while ( $row = mysql_fetch_assoc($result)){
  $arr_id_tipo_assegno[] = $row;
 }
mysql_free_result($result);

//RECUPERO DA DB DEGLI STATI
$sqlStatus = 'SELECT * ';
$sqlStatus .= 'FROM status ';
$sqlStatus .= 'ORDER BY id_status';
$result = @mysql_query($sqlStatus,$db) or die("Query failed : " . mysql_error());
while ( $row = mysql_fetch_assoc($result)){
  $arrStatus[] = $row;
 }
mysql_free_result($result);

// RECUPERO da DB  DELLA SEDE
$sql = 'SELECT * ';
$sql .= 'FROM sedi ';
$sql .= 'ORDER BY id_sede asc';
$result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error() . " " .$sql);
while ( $row = mysql_fetch_assoc($result)){
  $arr_sede[] = $row;
 }
mysql_free_result($result);

// CHIUSURA CONNESSIONE AL DB
mysql_close($db)

?>
       
<h1><a>Richiesta Bando di concorso (Assegno di Ricerca)</a></h1>

<form id="form_43489" name="form_assegno" class="appnitro"  method="post" action="savedata.php" >
    <div class="form_description">
    <h2>Richiesta Bando di concorso (Assegno di Ricerca)</h2>
    <p></p>
    </div>						
    <ul>
    <li id="li_hidden" >
    <input type="hidden" id="id" name="id" value="<?php echo $id ?>"/>
    <input type="hidden" id="nome_richiedente" name="nome_richiedente" value="<?php echo $nome_richiedente?>"/>
    <input type="hidden" id="status" name="status" value= "<?php if($status!="") {echo $status ;} else {print "0";} ?>" />
    <input type="hidden" id="role" name="role" value="<?php echo $role ?>" />
    <input type="hidden" id="id_template" name="id_template" value="<?php echo $id_template ?>" />
    <input type="hidden" id="id_tipo_form" name="id_tipo_form" value="<?php echo $id_tipo_form ?>" />
    </li>
 	
<?php $html_id = 0?>
<?php print getDataRichiesta($html_id++, $data_richiesta); ?>
<?php print getIdAssegno($html_id++, $id_assegno, $role); ?>
    
<?php 
    print getProvvedimento ($html_id++, $role, $n_provvedimento, $data_provvedimento_DD, $data_provvedimento_MM, $data_provvedimento_YYYY); 
?>
<?php print getSedeLavoro($html_id++,$sede, $arr_sede); ?>

<?php print getTipologiaAssegno($html_id++,$arr_id_tipo_assegno, $id_tipo_assegno ); ?>

<?php print getPresaServizio($html_id++,$presa_servizio_DD, $presa_servizio_MM, $presa_servizio_YYYY); ?>

<?php print getMembriCommissione($html_id++,$membri_commissione); ?>
<?php print getDataColloquio($html_id++, $data_colloquio_1_DD, $data_colloquio_1_MM, 
$data_colloquio_1_YYYY, $ora_colloquio_1); ?>

<?php print getScadenzaBando($html_id++,$scadenza_bando_DD,$scadenza_bando_MM,$scadenza_bando_YYYY) ?>
<?php print getNomeEnteErogatore ($html_id++,$ambito_ricerca); ?>
<?php print getCoperturaFinanziaria ($html_id++,$arr_commesse, $commessa_modulo); ?>
<?php print getTema($html_id++,$tema); ?>
<?php print getResponsabile($html_id++,$responsabile); ?>
<?php print getDurata($html_id++,(int)$durata); ?>
<?php print getImporto($html_id++,$importo_cifre, $importo_lettere); ?>
<?php print getFondiByRole($html_id++,$role, $fondi_ordinari); ?>
<?php print getLauree($html_id++,$requisiti_lauree, $requisiti_lauree_def, isset($_POST['id'])); ?>
<?php print getEsperienze($html_id++,$requisiti_esperienze,$requisiti_esperienze_def, isset($_POST['id'])); ?>
<?php print getNomeRichiedente ($html_id++,$nome_richiedente_esteso); ?>
     
 
  <li class="buttons">
  <input type="hidden" name="form_id" value="43489" />
  <?php
    echo "<table width=\"450px\">\n";
    echo "<tr>\n";
    echo "<td>\n";
     if (!isset($_POST['id'])) {
       echo "<input id=\"saveForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Salva\" onclick=\"return true;\"  />";
       echo "</td>\n";
       //       echo "<input type=\"hidden\" id=\"mail2admin\" name=\"mail2admin\" value=\"on\"/>" ;
       echo "<td colspan=4> <input type=CHECKBOX name=\"mail2admin\" >Invia mail ad Admin</td>\n";
       //echo "<td colspan=4> <input type=CHECKBOX name=\"mail2admin\" checked=\"checked\">Invia mail ad Admin</td>\n";
     } else {
       echo "<input id=\"saveForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Aggiorna\" onclick=\"return true;\" />\n";
       echo "</td>\n";
       if ($role == "admin") {
	 for ($i = 0 ; $i < sizeof($arrStatus); $i++) {
	   echo "<td style=\"border-width: 1px; border-color: #000080; border-style: solid; background-color:#E0FFFF; vertical-align:middle \">\n";
	   $value = $arrStatus[$i];
	   echo "<label for=\"status_".$value['id_status']."\"><input type=\"radio\" id=\"status_".$value['id_status']."\" name=\"status\" value=\"".$value['id_status']."\"";
	   if($i == $status) echo " checked=\"checked\"";
	   echo "/>" . $value['message'] . "</label>\n";
	   echo "</td>\n";
	 }
       } else {
	 //	 echo "<td colspan=4> <input type=CHECKBOX name=\"mail2admin\" checked=\"checked\">Invia mail ad Admin</td>\n";
	 echo "<td colspan=4> <input type=CHECKBOX name=\"mail2admin\" >Invia mail ad Admin</td>\n";
       }
       echo "</tr>\n";
       echo "<tr>\n";
       echo "<td>
                <label class=\"description\" for=\"commento\">Commento</label> </td>\n";
       echo "<td align=\"center\" colspan=\"4\">
                  <textarea id=\"commento_1\" name=\"commento\" class=\"element_textarea_medium\" cols=\"40\" rows=\"6\" ";
       if ($role != "admin") {
	 echo "readonly=\"readonly\" ";
       }
       echo ">$commento</textarea> <p class=\"guidelines\" id=\"guide_status\"><small>Status della richiesta</small></p>
             </td>\n";
       
     }
echo "</tr>";
echo "<tr>";
echo "<td>";
echo "  <input type=\"reset\"  class=\"button_text\" value=\"Reset\" onclick=\"return confirm('Are you sure you want to clear?');\"/>";
echo "</td>";
echo "</tr>";

echo "</table> \n";

?>
</li>

</ul>

</form>	

<table>
  <tr>
    <td>
     <form id="form_annulla" name="form_annulla" class="appnitro"  method="post" action="lista.php" >
       <input type="hidden" id="nome_richiedente_annulla" name="nome_richiedente" value="<?php echo $nome_richiedente?>" />
       <input id="annulla" class="button_text" type="button" name="annulla" value="Annulla" onClick="if (confirm('Confermi l\'uscita?')) document.location.href='lista.php' "/>
     </form>	
    </td>
  </tr>
</table>


</div>
  
<img id="bottom" src="bottom.png" alt=""/>
</body>
 
</html>
