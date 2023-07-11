<?php
include 'config.php';

session_start();

//IL CHECK NON FUNZIONA!!
if(!session_is_registered($_SESSION['user'])){
  header( "Location: login.php" );
 } 


require_once('../PHPMailer/class.phpmailer.php');

function getEmailAddresses($sql,$con) {

  $result = @mysql_query($sql,$con) or die("Query 1 failed : " . mysql_error()." | ".$sql);
  $count = @mysql_num_rows($result);
  //  echo "count = ".$count."<br>";
  while ( $count > 1 ) {
    $row = mysql_fetch_assoc($result);
    $addresses[$count] = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";
    //    echo "addresses[".$count."]".$addresses[$count]."<br>";
    $count--;
  }
  $row = mysql_fetch_assoc($result);
  $addresses[$count] = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";
  
  //    echo "addresses[".$count."]".$addresses[$count]."<br>";
  mysql_free_result($result);

  return $addresses;
}


function sendEmailtoAdmin ($from, $userAddr, $adminsAddr , $isInsert, $stato, $commento, $role, $resume) {
  
  $mail = new PHPMailer(); // defaults to using php "mail()"
  
  /*  if ( $isInsert == true ) {
    $body = "E' stata inserita una nuova richiesta di bando di assegno di ricerca.<br><br>";
  } else {
    $body = "E' stata aggiornata una richiesta di bando di assegno di ricerca<br><br>";
    }*/


  if($debug_mail==1){
    echo "from: $from<br>";
  }

  foreach ($adminsAddr as $value) {
    $mail->AddAddress($value, "");
   if($debug_mail==1){
     echo "admin: $value<br />\n";
   }
  }
  foreach ($userAddr as $value) {
    $mail->AddAddress($value);
    if($debug_mail==1){
      echo "user: $value<br />\n";
    }
  }

  $body = $resume;
    
  if ($role == "admin") {
    if($commento != "") {
      $body .= "L'amministratore ha inserito il seguente commento: <br><br><strong>".$commento."</strong><br><br>";
    }
    $body .= "Stato della richiesta: <strong>".$stato."</strong><br><br><em>L'Amministratore: ".$_SESSION['user']."</em><br>";
  } else {
    //    $body .= $resume;
  }
  
  $mail->IsSMTP(); // telling the class to use SMTP
  
  $mail->SMTPAuth   = false;                  // enable SMTP authentication
  $mail->Host       = "mail-ipv4.ilc.cnr.it"; // sets the SMTP server
  $mail->Port       = 25;                    // set the SMTP port 
  
  
  $mail->SetFrom($from, '');
  $mail->AddReplyTo($from,'');
  
  $i=1;


  $mail->Subject    = "Richiesta di bando di assegno di ricerca";
  
  //  $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
  
  if($debug_mail==1){
    echo "body = $body<br>";
  }

  $mail->MsgHTML($body);

  if(!$mail->Send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
  } else {
    //echo "Message sent to ($to)!";
  }
    
}


function getId_Assegno($con) {

  //SELECT STR_TO_DATE(data_chiusura,"%d-%m-%Y") FROM assegni where data_chiusura != "" order by  STR_TO_DATE(data_chiusura,"%d-%m-%Y") asc

  // Trova l'id dell'ultimo assegno chiuso in quest'anno
  //  SELECT id_assegno FROM assegni a where YEAR (STR_TO_DATE(a.data_chiusura,"%d-%m-%Y")) = YEAR(CURDATE())
  
  $sql = 'SELECT max(id_assegno) as max FROM assegni a where YEAR (STR_TO_DATE(a.data_chiusura,"%d-%m-%Y")) = YEAR(CURDATE())';
  //$sql = 'SELECT id_assegno FROM assegni a where YEAR (STR_TO_DATE(a.data_chiusura,"%d-%m-%Y")) = "2012"';

  $result = mysql_query($sql,$con) or die("Query failed : " . mysql_error());

  if ($last_id = mysql_fetch_assoc($result)) {
    $id_assegno = $last_id['max'] + 1;
    echo "il nuovo id e' " .$id_assegno . "<br>";
  } else {
    //primo nell'anno
    echo "primo nell'anno " .$id_assegno . "<br>";
    
    $id_assegno = 1;
  }
  echo "il nuovo id e' " .$id_assegno . "<br>";
  mysql_free_result($result);

  return $id_assegno;
  
}


//RACCOLGO I DATI DA $_POST

$id = $_POST['id'];
$mail2admin = $_POST['mail2admin'];

if ($mail2admin == "on") {
  $data_richiesta = $_POST['data_richiesta'];
 } else {
  $data_richiesta = '';
 }

$id_assegno = $_POST['id_assegno'];
echo ($id_assegno);
$presa_servizio_DD = $_POST['presa_servizio_DD'];
$presa_servizio_MM = $_POST['presa_servizio_MM'];
$presa_servizio_YYYY = $_POST['presa_servizio_YYYY'];
$membri = $_POST['membri'];
if( isset($_POST['date_colloquio']) ){
  $date_colloquio = $_POST['date_colloquio'];
} else {
  $date_colloquio = 0;
}
$data_colloquio_1_DD = $_POST['data_colloquio_1_DD'];
$data_colloquio_1_MM = $_POST['data_colloquio_1_MM'];
$data_colloquio_1_YYYY = $_POST['data_colloquio_1_YYYY'];
$data_colloquio_2_DD = $_POST['data_colloquio_2_DD'];
$data_colloquio_2_MM = $_POST['data_colloquio_2_MM'];
$data_colloquio_2_YYYY = $_POST['data_colloquio_2_YYYY'];
$data_colloquio_3_DD = $_POST['data_colloquio_3_DD'];
$data_colloquio_3_MM = $_POST['data_colloquio_3_MM'];
$data_colloquio_3_YYYY = $_POST['data_colloquio_3_YYYY'];

$n_provvedimento = $_POST['n_provvedimento'];
$data_provvedimento_DD   = $_POST['data_provvedimento_DD'];
$data_provvedimento_MM   = $_POST['data_provvedimento_MM'];
$data_provvedimento_YYYY = $_POST['data_provvedimento_YYYY'];

if ($n_provvedimento == '') {
  $n_provvedimento = '';
}
//print "(".$n_provvedimento.")";

/*$hh_colloquio_1 = $_POST['HH_1'];
$mm_colloquio_1 = $_POST['MM_1'];
$hh_colloquio_2 = $_POST['HH_2'];
$mm_colloquio_2 = $_POST['MM_2'];
$hh_colloquio_3 = $_POST['HH_3'];
$mm_colloquio_3 = $_POST['MM_3'];
*/

$ora_colloquio_1 = $_POST['HH_1'];
$ora_colloquio_2 = $_POST['HH_2'];
$ora_colloquio_3 = $_POST['HH_3'];

//echo $ora_colloquio_1."<br>";
//echo $min_colloquio_1."<br>";

$scadenza_bando_DD = $_POST['scadenza_bando_DD'];
$scadenza_bando_MM = $_POST['scadenza_bando_MM'];
$scadenza_bando_YYYY = $_POST['scadenza_bando_YYYY'];
$ambito_ricerca = $_POST['ambito_ricerca'];
$commessa_modulo = $_POST['commessa_modulo'];
$tema = $_POST['tema'];
$responsabile = $_POST['responsabile'];
$durata = $_POST['durata'];
$importo_cifre = $_POST['importo_cifre'];
$importo_lettere = $_POST['importo_lettere'];
$requisiti_lauree = $_POST['requisiti_lauree'];
$requisiti_esperienze = $_POST['requisiti_esperienze'];
$nome_richiedente = $_POST['nome_richiedente'];
$status = $_POST['status'];
$commento = $_POST['commento'];
$role = $_POST['role'];
$fondi_ordinari = $_POST['fondi_ordinari'];
$id_template = $_POST['id_template'];
$id_tipo_assegno = $_POST['id_tipo_assegno'];
$id_tipo_form = $_POST['id_tipo_form'];

$presa_servizio_data = $presa_servizio_DD.'-'.$presa_servizio_MM.'-'.$presa_servizio_YYYY;
$scadenza_bando_data = $scadenza_bando_DD .'-'.$scadenza_bando_MM .'-'.$scadenza_bando_YYYY;
$data_colloquio_1 = $data_colloquio_1_DD.'-'. $data_colloquio_1_MM.'-'. $data_colloquio_1_YYYY;
$data_colloquio_2 = $data_colloquio_2_DD.'-'. $data_colloquio_2_MM.'-'. $data_colloquio_2_YYYY;
$data_colloquio_3 = $data_colloquio_3_DD.'-'. $data_colloquio_3_MM.'-'. $data_colloquio_3_YYYY;

$data_provvedimento = $data_provvedimento_DD.'-'. $data_provvedimento_MM.'-'. $data_provvedimento_YYYY;

/*
$ora_colloquio_1 = $hh_colloquio_1.':'.$mm_colloquio_1;
$ora_colloquio_2 = $hh_colloquio_2.':'.$mm_colloquio_2;
$ora_colloquio_3 = $hh_colloquio_3.':'.$mm_colloquio_3;
*/

$importo = $importo_cifre.' '.$importo_lettere;

//echo "date_colloquio: $date_colloquio<br>";



$con = mysql_connect($dbhost, $dbuser, $dbpwd);
if (!$con)
  {
    die('Could not connect: ' . mysql_error());
  }

mysql_select_db($dbname, $con);

//solo ora si puo' fare la real_escape: ha bisogno di una connesione aperta
$ambito_ricerca = mysql_real_escape_string($ambito_ricerca);
$requisiti_lauree = mysql_real_escape_string($requisiti_lauree);
$requisiti_esperienze = mysql_real_escape_string($requisiti_esperienze);
$tema      = mysql_real_escape_string($tema);
$membri    = mysql_real_escape_string($membri);
$responsabile    = mysql_real_escape_string($responsabile);
$commento    = mysql_real_escape_string($commento);

$id_sede = $_POST['sede'];


$debug = 0;
if ($debug==1){
  
  echo "id = ($id)<br/>";
  echo "data_richiesta = $data_richiesta<br/>";
  echo "presa_servizio_DD = $presa_servizio_DD<br/>";
  echo "presa_servizio_MM = $presa_servizio_MM<br/>";
  echo "presa_servizio_YYYY = $presa_servizio_YYYY<br/>";
  echo "membri = $membri<br/>";

  echo "date_colloquio = $date_colloquio<br/>";
  echo "data_colloquio_1_DD = $data_colloquio_1_DD<br/>";
  echo "data_colloquio_1_MM = $data_colloquio_1_MM<br/>";
  echo "data_colloquio_1_YYYY = $data_colloquio_1_YYYY<br/>";
  echo "data_colloquio_2_DD = $data_colloquio_2_DD<br/>";
  echo "data_colloquio_2_MM = $data_colloquio_2_MM<br/>";
  echo "data_colloquio_2_YYYY = $data_colloquio_2_YYYY<br/>";
  echo "data_colloquio_3_DD = $data_colloquio_3_DD<br/>";
  echo "data_colloquio_3_MM = $data_colloquio_3_MM<br/>";
  echo "data_colloquio_3_YYYY = $data_colloquio_3_YYYY<br/>";

  echo "scadenza_bando_DD = $scadenza_bando_DD<br/>";
  echo "scadenza_bando_MM = $scadenza_bando_MM<br/>";
  echo "scadenza_bando_YYYY = $scadenza_bando_YYYY<br/>";
  echo "ambito_ricerca = $ambito_ricerca<br/>";
  echo "commessa_modulo = $commessa_modulo<br/>";
  echo "tema = $tema<br/>";
  echo "responsabile = $responsabile<br/>";
  echo "durata = $durata<br/>";
  echo "importo_cifre = $importo_cifre<br/>";
  echo "importo_lettere = $importo_lettere<br/>";
  echo "requisiti_lauree = $requisiti_lauree<br/>";
  echo "requisiti_esperienze = $requisiti_esperienze<br/>";
  echo "nome_richiedente = $nome_richiedente<br/>";
  echo "role = $role<br/>";
  echo "fondi_ordinari = $fondi_ordinari<br/>";
 }



if ($id != -1) {

  if ($status == 3) { /* status di chiuso*/

    /* controllo se un bando era gia' chiuso xche' vuol dire che sto modificando uno chiuso
      e quindi non si deve modificare l'id*/

    $sql = "SELECT id_assegno FROM assegni WHERE id=".$id;

    $result = mysql_query($sql,$con) or die("Query failed : " . mysql_error());

    $res = mysql_fetch_assoc($result);
    mysql_free_result($result);
    
    if ($debug==1){
      echo "res = " .$res['id_assegno']."<br>";
    }

    if ( $res['id_assegno'] == 0) {
      
      /* prendo l'ultimo id_assegno e lo incremento di 1 e aggiorno quello appena chiuso  */
#      $last_id = "SELECT MAX(id_assegno)as max FROM assegni";
#      $last_id = "SELECT id_assegno as max FROM assegni where id = (SELECT MAX(id) FROM assegni where data_richiesta is not null)";


      // $id_assegno = getId_Assegno($con); //31.10.2022 Ora viene messo a mano tramite la form

      echo "id_assegno (" .$id_assegno.")<br/>";
/*
      $last_id = "SELECT id_assegno as max FROM assegni where id = (SELECT MAX(id) FROM assegni where anno = (SELECT MAX(anno) FROM assegni))";
      $result = mysql_query($last_id,$con) or die("Query failed : " . mysql_error());
      $max = mysql_fetch_assoc($result);
      if ($debug==1){
	echo "max = $max[max]<br>";
      }
      if ( $max['max'] == 0) {
	$id_assegno = 7;
      } else {
	$id_assegno = $max['max'] + 1;
      }
      mysql_free_result($result);
*/



    }
  }

  $update = "UPDATE assegni  SET ";
  if ( $id_assegno != 0 ) {
    $update .= "id_assegno = $id_assegno, ";
    $today = getdate();
    $data_chiusura = $today['mday']."-".$today['mon']."-".$today['year'];
    $update .= "data_chiusura = '$data_chiusura', ";
    $YY = substr($today['year'],-2);
    print_r( "YY: ". $YY);
    $update .= "anno = '$YY', ";
  }
  

  if ($mail2admin == "on") {
    $update .="data_richiesta = '$data_richiesta',";
  }

  $update .=" data_servizio = '$presa_servizio_data', membri_commissione = '$membri', date_colloquio = '$date_colloquio', data_colloquio_1 = '$data_colloquio_1',data_colloquio_2 = '$data_colloquio_2',data_colloquio_3 = '$data_colloquio_3', data_scadenza_bando = '$scadenza_bando_data', ambito_ricerca = '$ambito_ricerca', commessa_modulo = '$commessa_modulo', tema = '$tema', responsabile = '$responsabile', durata = '$durata', importo = '$importo', requisiti_lauree = '$requisiti_lauree', requisiti_esperienze = '$requisiti_esperienze', nome_richiedente = '$nome_richiedente', status = '$status', commento = '$commento', ora_colloquio_1 = '$ora_colloquio_1',ora_colloquio_2 = '$ora_colloquio_2',ora_colloquio_3 = '$ora_colloquio_3' , id_sede = $id_sede, fondi_ordinari = $fondi_ordinari, id_template = $id_template, id_tipo_assegno = $id_tipo_assegno, n_provvedimento = '$n_provvedimento', data_provvedimento = '$data_provvedimento', id_tipo_form = '$id_tipo_form' where id = ". $id . ";";
  
  if ($debug == 1) 
    echo $update."<br/>";
  $result = mysql_query($update,$con);
  $isInsert = false;
 }else {
  
  //  $insert = "INSERT INTO assegni  (id_assegno, data_richiesta, data_servizio, membri_commissione, date_colloquio, data_colloquio_1, data_colloquio_2, data_colloquio_3, data_scadenza_bando, ambito_ricerca, commessa_modulo, tema, responsabile, durata, importo, requisiti, nome_richiedente, status, commento) VALUES (0,'$data_richiesta','$presa_servizio_data','$membri','$date_colloquio','$data_colloquio_1','$data_colloquio_2','$data_colloquio_3','$scadenza_bando_data','$ambito_ricerca','$commessa_modulo','$tema','$responsabile','$durata','$importo','$requisiti','$nome_richiedente', 0, '$commento');";
  $insert = "INSERT INTO assegni (id_assegno, data_richiesta, data_servizio, membri_commissione, date_colloquio, data_colloquio_1, data_colloquio_2, data_colloquio_3, data_scadenza_bando, ambito_ricerca, commessa_modulo, tema, responsabile, durata, importo, requisiti_lauree, requisiti_esperienze, nome_richiedente, status, commento, ora_colloquio_1, ora_colloquio_2, ora_colloquio_3, id_template, id_sede, fondi_ordinari, id_tipo_assegno, n_provvedimento, data_provvedimento, id_tipo_form) VALUES (0,";
  if ($mail2admin == "on") {
    $insert .= "'$data_richiesta',";
  } else {
    $insert .= "NULL,";
  }
  $insert .= "'$presa_servizio_data','$membri','$date_colloquio','$data_colloquio_1','$data_colloquio_2','$data_colloquio_3','$scadenza_bando_data','$ambito_ricerca','$commessa_modulo','$tema','$responsabile','$durata','$importo','$requisiti_lauree','$requisiti_esperienze','$nome_richiedente', 0, '$commento', '$ora_colloquio_1','$ora_colloquio_2','$ora_colloquio_3', $id_template, $id_sede, $fondi_ordinari, $id_tipo_assegno, '$n_provvedimento', '$data_provvedimento', '$id_tipo_form');";
  if ($debug == 1) 
    echo $insert."<br/>";
  $result = mysql_query($insert,$con);
  $isInsert = true;
 }


if (!$result) {
  die('Invalid query 1: ' . mysql_error(). ' '.($isInsert?$insert:$update));
 }


if (!$result) {
  echo "<form name=\"savedata\" id=\"savedata1\" method=\"post\" action=errore.php>";
  echo "<input type=\"hidden\" id=\"err_id\" name=\"errore\" value=".$result.">";
  echo "</form>";
  
 } else {

  
  //prendo gli admin
  $sql  = "SELECT nome,cognome FROM users ";
  $sql .= "WHERE userRole = 'admin'";
  

  $adminsAddr = getEmailAddresses($sql,$con);

  $sql  = "SELECT nome,cognome FROM users ";
  $sql .= "WHERE userName = '".$nome_richiedente."'";
  //  echo "nome richiedente=".$nome_richiedente."<br>";

  $userAddr = getEmailAddresses($sql,$con);

  if ($role == "admin") {
    $sql  = "SELECT nome,cognome FROM users ";
    $sql .= "WHERE userName = '".$_SESSION['user']."'";
    
    $senderAdmin = getEmailAddresses($sql,$con);
    $from = $senderAdmin[1];
  } else {
    $from = $userAddr[1];
  }
if ( !is_bool($result)) {
  mysql_free_result($result);
}
  /*  
  $result = @mysql_query($sql,$con) or die("Query 1 failed : " . mysql_error()." | ".$sql);
  $count = @mysql_num_rows($result);
  
  while ( $count > 1 ) {
    $row = mysql_fetch_assoc($result);
    $addresses[$count] = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";
    
    $count--;
  }
  $row = mysql_fetch_assoc($result);
  $addresses[$count] = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";

  $sql  = "SELECT nome,cognome FROM users ";
  $sql .= "WHERE userName = '".$nome_richiedente."'";
    
  if ($role == "admin") {
  } else {
    $sql .= "WHERE userRole = 'admin'";
  }

  $result = @mysql_query($sql,$con) or die("Query 1 failed : " . mysql_error()." | ".$sql);
  $count = @mysql_num_rows($result);
  
  while ( $count > 1 ) {
    $row = mysql_fetch_assoc($result);
    $addresses[$count] = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";
    
    $count--;
  }
  $row = mysql_fetch_assoc($result);
  $addresses[$count] = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";

  $from = strtolower($row ["nome"]) .".". strtolower($row["cognome"])."@ilc.cnr.it";

  mysql_free_result($result);

  */


  $sql = "SELECT message FROM status WHERE id_status = ".$status ;
  $result = @mysql_query($sql,$con) or die("Query 2 failed : " . mysql_error()." | ".$sql);
  $row = mysql_fetch_assoc($result);
  $stato = $row['message'];
  mysql_free_result($result);

  if ($role == "admin") {
    $resume = "L'amministratore ha ";
  } else {
    $resume = "L'utente <strong>$nome_richiedente</strong> ha ";
  }
  if ($id != -1) {
    $resume .= "aggionato " ;
  } else {
    $resume .= "inserito ";
  }
  
  $resume .= " la richiesta con le seguenti informazioni:<br><br><em>Responsabile</em>: $responsabile<br><em>Tema</em>: $tema<br>";
  if ($sendEmail && (($mail2admin == "on") || ($role == "admin"))) {
    sendEmailtoAdmin($from, $userAddr, $adminsAddr,$isInsert, $stato, $commento, $role, $resume);
#    echo "manda email";
  } else {
#    echo "NON manda email";
  }

  echo "<form name=\"savedata\" id=\"savedata1\" method=\"post\" action=lista.php>";
  echo "<input type=\"hidden\" id=\"user_id\" name=\"utente\" value=\"nome_utente\">";
  echo "</form>";

 }

mysql_close($con);

?>


<script type="text/javascript">
  setTimeout('document.savedata.submit()',0);
</script>
