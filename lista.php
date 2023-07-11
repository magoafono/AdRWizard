<?php
session_start();
include 'config.php';
if(session_is_registered($_SESSION['user'])) {

$login = $_SESSION['user'];
$role  = $_SESSION['role'];
//echo "$login = (".$_SESSION['user'];
//echo ") $role = (".$_SESSION['role'].")<br>";

  echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
  echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n";
  echo "<head>\n";
  echo "<meta http-equiv=\"Content-Type\" content=\"text/html charset=UTF-8\"/>\n";
  echo "<title>Richiesta Bando di concorso (Assegno di Ricerca)</title>\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"table.css\" media=\"all\"/>\n";
  echo "<script type=\"text/javascript\" src=\"view.js\"></script>\n";
  echo "<script type=\"text/javascript\" >
         function inRow (el) {
           el.style.backgroundColor ='#E0FFFF';
         }
         function outRow (el) {
           el.style.backgroundColor ='';
         }
        </script>";

  echo "</head>\n";
  echo "<body id=\"main_body\">\n";
  
  if ($role =="admin") {
    echo "  <h1>Lista delle richieste per Admin</h1>\n";
  } else {
    echo "  <h1>Lista delle richieste di $login</h1>\n";
  }

  if(!($db = @mysql_connect($dbhost, $dbuser, $dbpwd))) //connect to database
    die("Couldn't connect to the database.");
  if(!@mysql_select_db($dbname, $db)) //select database
    die("Database doesn't exist!");

  $debug = 0;
  session_start();


  if (1==$debug) {
    echo "nome_richiedente (".$_SESSION['user'].") ";
    echo "role (".$role.")<br>";
  }
  $sql = 'SELECT * ';
  if ( $role == 'admin') {
    $sql .= 'FROM assegni';
  } else {
    $sql .= 'FROM assegni where nome_richiedente = \''.$login.'\'';
  }
  
  if (1==$debug) {
    echo "$sql";
  }
  //  print_r($sql);

  $result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error());



// For each result that we got from the Database
  
  echo "<table class=\"lista\">\n";
  echo "<tr>\n";
  echo "<!--th scope=\"col\" class=\"nobg\"></th-->\n";
  echo "<th scope=\"col\" abbr=\"Tema\">Tema</th>\n";
  echo "<th scope=\"col\" abbr=\"Responsabile\">Responsabile</th>\n";
  echo "<th scope=\"col\" abbr=\"Richiedente\">Richiedente</th>\n";
  echo "<th scope=\"col\" abbr=\"Data Richiesta\">Data richiesta</th>\n";
  echo "<th scope=\"col\" abbr=\"Data Scadenza\">Data Scadenza</th>\n";
  echo "<th scope=\"col\" abbr=\"Modulo\">Modulo</th>\n";
  echo "<th scope=\"col\" abbr=\"Responsabile Modulo\">Responsabile Modulo</th>\n";
  echo "<th scope=\"col\" abbr=\"Stato\">Stato</th>\n";
  echo "</tr>\n";
  
  if (!$result) {
    echo "Could not successfully run query ($sql) from DB: " . mysql_error();
    exit;
  }
  

  //Carico la form di default che e' l'ultima inserita nel DB

  $sql  = 'select name, id_form ';
  $sql .= 'from form f order by id_form desc LIMIT 1 ';
  $resultForm = @mysql_query($sql,$db) or die("Query tipo form failed : " . mysql_error() . " " .$sql);
  $rowNameForm = mysql_fetch_assoc($resultForm);
  $name_form = $rowNameForm['name'];
  mysql_free_result($resultForm);


  while ( $row = mysql_fetch_assoc($result)){
    $id = $row["id"];
    $id_assegno = $row["id_assegno"];
    $anno = $row["anno"];
    
    $data_chiusura = $row["data_chiusura"];
    $data_richiesta = $row["data_richiesta"];
    
    $data_servizio = $row["data_servizio"];
    list($presa_servizio_DD, $presa_servizio_MM,$presa_servizio_YYYY) =
      split("-", $data_servizio, 3);
    
    $membri_commissione = $row["membri_commissione"];
    $date_colloquio = $row["date_colloquio"];
    if ( $date_colloquio != 0) {
      $data_colloquio = $row["data_colloquio_".$date_colloquio];
      $ora_colloquio = $row["ora_colloquio_".$date_colloquio];
    } else {
      $data_colloquio = "";
      $ora_colloquio = "";
    }
    
    
    $scadenza_bando = $row["data_scadenza_bando"];
    list($scadenza_bando_DD, $scadenza_bando_MM,$scadenza_bando_YYYY) =
      split("-", $scadenza_bando, 3);
    
    $ambito_ricerca = $row["ambito_ricerca"];
    $commessa_modulo = $row["commessa_modulo"];
    
    $tema               = $row["tema"];
    $responsabile = $row["responsabile"];
    $durata = $row["durata"];
    $importo            = $row["importo"];
    $requisiti_lauree = $row["requisiti_lauree"];
    $requisiti_esperienze = $row["requisiti_esperienze"];
    $nome_richiedente = $row["nome_richiedente"];
    
    $id_status = $row["status"];
    
    $id_template = $row["id_template"];
    $id_tipo_assegno = $row["id_tipo_assegno"];
    $id_sede = $row["id_sede"];
    $fondi_ordinari = $row["fondi_ordinari"];
    
    $id_tipo_form = $row["id_tipo_form"];

    $sqlUser = 'SELECT * ';
    $sqlUser .= "FROM users WHERE userName = '".$nome_richiedente."'";
    $resultUser = @mysql_query($sqlUser,$db) or die("Query failed : " . mysql_error() . " " .$sqlUser);
    
    $rowUser = mysql_fetch_assoc($resultUser);
    $nome_richiedente_esteso = $rowUser ["nome"] ." ". $rowUser["cognome"];
    mysql_free_result($resultUser);
    
    $sqlStatus = 'SELECT * ';
    $sqlStatus .= 'FROM status WHERE id_status = '.$id_status;
    
    $resultStatus = @mysql_query($sqlStatus,$db) or die("Query failed : " . mysql_error());
    
    // For each result that we got from the Database
    
    $rowStatus = mysql_fetch_assoc($resultStatus);
    $status = $rowStatus['message'];
    $status_color = $rowStatus['bgcolor'];
    //retrieve della commessa
    mysql_free_result($resultStatus);
    
    $sqlCommesse  = 'SELECT * ';
    $sqlCommesse .= 'FROM commesse WHERE id = '.$commessa_modulo;
    

    $resCommesse = @mysql_query($sqlCommesse,$db) or die("Query commesse failed : " . mysql_error());
    
    $rowCommesse = mysql_fetch_assoc($resCommesse);
    
    $commessa_id_modulo = $rowCommesse['id_modulo'];
    $commessa_descr_modulo = $rowCommesse['descr_modulo'];
    $commessa_id_comm = $rowCommesse['id_comm'];
    $commessa_descr_comm = $rowCommesse['descr_comm'];
    $commessa_responsabile = $rowCommesse['responsabile'];
    
    //RECUPERO IL NOME DEL SEDE
    
    $sqlSede    = 'SELECT luogo ';
    $sqlSede   .= 'FROM sedi WHERE id_sede = '.$id_sede ;
    $resultSede = @mysql_query($sqlSede,$db) or die("Query sede failed : " . mysql_error(). " " .$sqlSede);
    $rowSede    = mysql_fetch_assoc($resultSede);
    
    // print_r ($sqlSede);
    
    $sede = $rowSede['luogo'];
    
    mysql_free_result($resultSede);
    
    //RECUPERO IL nome e filename DEL TEMPLATE

    $sqlTemplate    = 'SELECT nome, filename ';
    $sqlTemplate   .= 'FROM template WHERE id_template = '.$id_template;
    $resultTemplate = @mysql_query($sqlTemplate,$db) or die("Query template failed : " . mysql_error() . " " .$sqlTemplate);
    $rowTemplate    = mysql_fetch_assoc($resultTemplate);
    $template = $rowTemplate['filename'];
    $template_name = $rowTemplate['nome'];
    mysql_free_result($resultTemplate);
    
    //RECUPERO DEL NOME DELL'ASSEGNO
    if ( $id_tipo_assegno != NULL ) {
      $sql  = 'SELECT nome ';
      $sql .= 'FROM tipo_assegni WHERE id_tipo_assegno = ' . $id_tipo_assegno;
      $resultAssegno = @mysql_query($sql,$db) or die("Query tipo_assegno failed : " . mysql_error() . " " .$sql);
      $rowTipoAssegni = mysql_fetch_assoc($resultAssegno);
      $tipo_assegno = $rowTipoAssegni['nome'];
      mysql_free_result($resultAssegno);
    } else {
      $tipo_assegno = "";
    }
    
    /** Scommentato il 29.12.2022: recupero, se esiste, il form di quando è stato creato l'assegno, altrimenti prendo l'ultimo.
	commentato il 13.05.2014: non ho capito perche' non si va a prendere sempre il form piu' recente
       tolta la parte che ricerca l'ultimo form utilizzato da quell'utente */
      //echo "id_assegno " . $id_assegno;  
      //Recupero il nome della form da utilizzare in base alla normativa vigente
      if ( $id_assegno != 0 ) {
	$sql  = 'SELECT f.name ';
	$sql .= 'FROM form f, assegni a WHERE a.id_tipo_form = f.id_form AND a.id_assegno = ' . $id_assegno .' AND a.anno = '.$anno;
	$resultForm = @mysql_query($sql,$db) or die("Query tipo form failed : " . mysql_error() . " " .$sql);
      } else {
    /* */
      //Prendi l'ultimo che dovrebbe essere il piu' recente //select MAX(f.id_form) from form f  where   f.id_form 
      $sql  = 'select name, id_form ';
      $sql .= 'from form f order by id_form desc LIMIT 1 ';
      $resultForm = @mysql_query($sql,$db) or die("Query tipo form failed : " . mysql_error() . " " .$sql);
      } //ripristinato il 29.12.2022 => vedi commento del 13.05.2014 appena sopra
      //    print_r ($sql);
    $rowNameForm = mysql_fetch_assoc($resultForm);
    $name_form = $rowNameForm['name'];
    mysql_free_result($resultForm);
  
    //Provvedimento
    $n_provvedimento = $row['n_provvedimento'];
    $data_provvedimento = $row['data_provvedimento'];

    //  echo "<tr class=\"off\" onmouseover=\"this.className='on';this.style.cursor='pointer';\" onmouseout=\"this.className='off'\" onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">\n";
    echo "<tr class=\"off\" onmouseover=\"this.className='on';this.style.cursor='pointer';\" onmouseout=\"this.className='off'\" >\n";
    echo "<td style=\"display:none;\">\n";
    
    if ( ($role == "admin") || (($id_status != 1) && ($id_status != 3))){
      
	echo "<form id=\"form_mod_$id\" name=\"form_mod_assegno_$id\" class=\"appnitro\" method=\"post\" action=\"form-$name_form.php\">\n";
/*      switch ($id_template) {
      case "0":
	echo "<form id=\"form_mod_$id\" name=\"form_mod_assegno_$id\" class=\"appnitro\" method=\"post\" action=\"form-2009.php\">\n";
	break;
      case "1":
	echo "<form id=\"form_mod_$id\" name=\"form_mod_assegno_$id\" class=\"appnitro\" method=\"post\" action=\"form-2011.php\">\n";
	break;
      case "2":
	echo "<form id=\"form_mod_$id\" name=\"form_mod_assegno_$id\" class=\"appnitro\" method=\"post\" action=\"form-2012.php\">\n";
	break;
      } 
*/
      //    echo "<a href=\"\" onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">modifica</a>";
      echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\"/>\n";
      echo "<input type=\"hidden\" id=\"role\" name=\"role\" value=\"$role\"/>\n";
      echo "</form>\n";
      
    } else {
      //    echo "<div align=center>chiuso</div>\n";
    }
    
    echo "</td>\n";
    if (strlen($tema)>100 ) {
      echo "<td   onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\"><p style=\"font-size: 10pt\">".substr($tema,0,100)." ...</p></td>\n"; 
    }
    else {
      echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\" title=\"Ciao\" >".$tema."</td>\n"; 
    }
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">$responsabile</td>\n";
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">$nome_richiedente_esteso</td>\n";
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">$data_richiesta</td>\n";
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">$scadenza_bando</td>\n";
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">$commessa_id_modulo</td>\n";
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\">$commessa_responsabile</td>\n";
    echo "<td onclick=\"javascript: document.form_mod_assegno_$id.submit();return false;\" style=\"background-color:$status_color\">$status</td>\n";
    
    //  echo "<td style=\"display:none;\">\n";
    echo "<td>\n";
    echo "<form id=\"form_$id\" name=\"form_assegno_$id\" class=\"appnitro\"  method=\"post\" action=\"render.php\">\n";
    echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\" />\n";
    echo "<input type=\"hidden\" id=\"anno\" name=\"anno\" value=\"$anno\" />\n";
    echo "<input type=\"hidden\" id=\"id_assegno\" name=\"id_assegno\" value=\"$id_assegno\" />\n";
    echo "<input type=\"hidden\" id=\"id_status\" name=\"id_status\" value=\"$id_status\" />\n";
    echo "<input type=\"hidden\" id=\"data_chiusura\" name=\"data_chiusura\" value=\"$data_chiusura\" />\n";
    echo "<input type=\"hidden\" id=\"data_richiesta\" name=\"data_richiesta\" value=\"$data_richiesta\" />\n";
    echo "<input type=\"hidden\" id=\"data_servizio\" name=\"data_servizio\" value=\"$data_servizio\" />\n";
    echo "<input type=\"hidden\" id=\"membri_commissione\" name=\"membri_commissione\" value=\"$membri_commissione\"/>\n";
    echo "<input type=\"hidden\" id=\"data_colloquio\" name=\"data_colloquio\" value=\"$data_colloquio\"/>\n";
    echo "<input type=\"hidden\" id=\"ora_colloquio\" name=\"ora_colloquio\" value=\"$ora_colloquio\"/>\n";
    echo "<input type=\"hidden\" id=\"scadenza_bando\" name=\"scadenza_bando\" value=\"$scadenza_bando\"/>\n";
    echo "<input type=\"hidden\" id=\"ambito_ricerca\" name=\"ambito_ricerca\" value=\"".htmlspecialchars($ambito_ricerca)."\"/>\n";
    echo "<input type=\"hidden\" id=\"commessa_id_modulo\" name=\"commessa_id_modulo\" value=\"$commessa_id_modulo\"/>\n";
    echo "<input type=\"hidden\" id=\"commessa_descr_modulo\" name=\"commessa_descr_modulo\" value=\"$commessa_descr_modulo\"/>\n";
    echo "<input type=\"hidden\" id=\"commessa_id_comm\" name=\"commessa_id_comm\" value=\"$commessa_id_comm\"/>\n";
    echo "<input type=\"hidden\" id=\"commessa_descr_comm\" name=\"commessa_descr_comm\" value=\"$commessa_descr_comm\"/>\n";
    echo "<input type=\"hidden\" id=\"commessa_responsabile\" name=\"commessa_responsabile\" value=\"$commessa_responsabile\"/>\n";
    //  echo "<input type=\"hidden\" id=\"tema\" name=\"tema\" value=\"".htmlspecialchars($tema)."\" />\n";
    echo "<input type=\"hidden\" id=\"tema\" name=\"tema\" value=\"".$tema."\" />\n";
    echo "<input type=\"hidden\" id=\"responsabile\" name=\"responsabile\" value=\"$responsabile\"/>\n";
    echo "<input type=\"hidden\" id=\"durata\" name=\"durata\" value=\"$durata\"/>\n";
    echo "<input type=\"hidden\" id=\"importo\" name=\"importo\" value=\"$importo\"/>\n";
    echo "<input type=\"hidden\" id=\"requisiti_lauree\" name=\"requisiti_lauree\" value=\"".htmlspecialchars($requisiti_lauree)."\"/>\n";
    echo "<input type=\"hidden\" id=\"requisiti_esperienze\" name=\"requisiti_esperienze\" value=\"".htmlspecialchars($requisiti_esperienze)."\"/>\n";
    echo "<input type=\"hidden\" id=\"nome_richiedente\" name=\"nome_richiedente\" value=\"$nome_richiedente\"/>\n";
    echo "<input type=\"hidden\" id=\"sede\" name=\"sede\" value=\"$sede\"/>\n";
    echo "<input type=\"hidden\" id=\"fondi_ordinari\" name=\"fondi_ordinari\" value=\"$fondi_ordinari\"/>\n";
    echo "<input type=\"hidden\" id=\"template\" name=\"template\" value=\"$template\"/>\n";
    echo "<input type=\"hidden\" id=\"template_name\" name=\"template_name\" value=\"$template_name\"/>\n";
    if ($tipo_assegno != ""){
      echo "<input type=\"hidden\" id=\"tipo_assegno\" name=\"tipo_assegno\" value=\"$tipo_assegno\"/>\n";
    }
    echo "<input type=\"hidden\" id=\"tipo_form\" name=\"tipo_form\" value=\"$id_tipo_form\"/>\n";
    echo "<input type=\"hidden\" id=\"n_provvedimento\" name=\"n_provvedimento\" value=\"$n_provvedimento\"/>\n";
    echo "<input type=\"hidden\" id=\"data_provvedimento\" name=\"data_provvedimento\" value=\"$data_provvedimento\"/>\n";
    echo "<table class=\"noborder\">\n";
    echo "<tr>\n";
    echo "<td>\n";
    echo "<input id=\"previewForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Preview\" onclick=\"this.form.target='_blank';return true;\"/>\n";
    echo "</td>\n";
    echo "<td>\n";
    echo "<input id=\"stampaForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"PDF\" onclick=\"this.form.target='_blank';return true;\"/>\n";
    echo "</td>\n";
//    echo "<td>\n";
 //   echo "<input id=\"docForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Doc\" onclick=\"this.form.target='_blank';return true;\"/>\n";
//    echo "</td>\n";
    echo "</tr>\n";
    echo "</table>\n";
    echo "</form>\n";
    echo "</td>\n";
    if ( $id_status != 3 ) {
      echo "<td> 
         <form id=\"form_delete_$id\" name=\"form_delete_assegno_$id\" class=\"appnitro\"  method=\"post\" action=\"delete.php\">\n
          <input id=\"deleteForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Elimina\" onclick=\"return confirm('Are you sure you want to delete?');\"/>\n";
      echo "<input type=\"hidden\" id=\"id\" name=\"id\" value=\"$id\"/>\n";
      echo "</form>\n";
      echo "</td>\n";
    } else {
      echo "<td align=\"center\">Bloccata</td>";
    }
    
    echo "</tr>\n";
    
  }
  echo "</table>\n";
  
  
  echo "<table class=\"tbbuttons\" width=\"80%\">
       <tr>
         <td align=\"center\">
";
  
  echo "<form id=\"new_form\" name=\"new_form_assegno\" class=\"appnitro\"  method=\"post\" action=\"form-".$name_form.".php\">\n";
  echo "<input id=\"newForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Nuova Richiesta\" />\n";
  echo "<input type=\"hidden\" id=\"nome_richiedente\" name=\"nome_richiedente\" value=\"$login\"/>\n";
  echo "<input type=\"hidden\" id=\"role\" name=\"role\" value=\"$role\"/>\n";
  echo "</form>
         </td>
         <td align=\"center\">
";
  echo "<form id=\"logout\" name=\"logout\" class=\"appnitro\"  method=\"post\" action=\"logout.php\">\n";
  echo "<input id=\"logout\" class=\"button_text\"  type=\"submit\" name=\"submit\" value=\"Logout\" onclick=\"return confirm('Are you sure you want to logout?');\" />\n";
  echo "<input type=\"hidden\" id=\"nome_richiedente\" name=\"nome_richiedente\" value=\"$login\"/>\n";
  echo "</form>
   </td>
   </tr>";

  echo "
   <tr>
   <td>
      <form id=\"changepwd\" name=\"changepwd\" class=\"appnitro\"  method=\"post\" action=\"change-pwd.php\">\n
        <input id=\"chpwd\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Cambia Password\" />\n
        <input type=\"hidden\" id=\"user\" name=\"user\" value=\"$login\"/>\n
      </form>
   </td>
   </tr>
   </table>
\n";


//  echo getcwd();
  mysql_free_result($result);
if($resCommesse != null) {
  mysql_free_result($resCommesse);
  }
  
  mysql_close($db);
  
  echo "</body>";
  
  echo "</html>";
  
 } else {
  header( "Location: login.php" );
  
 }

/*
echo "<table border=\"1\">";
echo "<tr><td>" .$_SERVER['argv'] ."</td><td>argv</td></tr>";
echo "<tr><td>" .$_SERVER['argc'] ."</td><td>argc</td></tr>";
echo "<tr><td>" .$_SERVER['GATEWAY_INTERFACE'] ."</td><td>GATEWAY_INTERFACE</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_ADDR'] ."</td><td>SERVER_ADDR</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_NAME'] ."</td><td>SERVER_NAME</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_SOFTWARE'] ."</td><td>SERVER_SOFTWARE</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_PROTOCOL'] ."</td><td>SERVER_PROTOCOL</td></tr>";
echo "<tr><td>" .$_SERVER['REQUEST_METHOD'] ."</td><td>REQUEST_METHOD</td></tr>";
echo "<tr><td>" .$_SERVER['REQUEST_TIME'] ."</td><td>REQUEST_TIME</td></tr>";
echo "<tr><td>" .$_SERVER['QUERY_STRING'] ."</td><td>QUERY_STRING</td></tr>";
echo "<tr><td>" .$_SERVER['DOCUMENT_ROOT'] ."</td><td>DOCUMENT_ROOT</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_ACCEPT'] ."</td><td>HTTP_ACCEPT</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_ACCEPT_CHARSET'] ."</td><td>HTTP_ACCEPT_CHARSET</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_ACCEPT_ENCODING'] ."</td><td>HTTP_ACCEPT_ENCODING</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_ACCEPT_LANGUAGE'] ."</td><td>HTTP_ACCEPT_LANGUAGE</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_CONNECTION'] ."</td><td>HTTP_CONNECTION</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_HOST'] ."</td><td>HTTP_HOST</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_REFERER'] ."</td><td>HTTP_REFERER</td></tr>";
echo "<tr><td>" .$_SERVER['HTTP_USER_AGENT'] ."</td><td>HTTP_USER_AGENT</td></tr>";
echo "<tr><td>" .$_SERVER['HTTPS'] ."</td><td>HTTPS</td></tr>";
echo "<tr><td>" .$_SERVER['REMOTE_ADDR'] ."</td><td>REMOTE_ADDR</td></tr>";
echo "<tr><td>" .$_SERVER['REMOTE_HOST'] ."</td><td>REMOTE_HOST</td></tr>";
echo "<tr><td>" .$_SERVER['REMOTE_PORT'] ."</td><td>REMOTE_PORT</td></tr>";
echo "<tr><td>" .$_SERVER['SCRIPT_FILENAME'] ."</td><td>SCRIPT_FILENAME</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_ADMIN'] ."</td><td>SERVER_ADMIN</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_PORT'] ."</td><td>SERVER_PORT</td></tr>";
echo "<tr><td>" .$_SERVER['SERVER_SIGNATURE'] ."</td><td>SERVER_SIGNATURE</td></tr>";
echo "<tr><td>" .$_SERVER['PATH_TRANSLATED'] ."</td><td>PATH_TRANSLATED</td></tr>";
echo "<tr><td>" .$_SERVER['SCRIPT_NAME'] ."</td><td>SCRIPT_NAME</td></tr>";
echo "<tr><td>" .$_SERVER['REQUEST_URI'] ."</td><td>REQUEST_URI</td></tr>";
echo "<tr><td>" .$_SERVER['PHP_AUTH_DIGEST'] ."</td><td>PHP_AUTH_DIGEST</td></tr>";
echo "<tr><td>" .$_SERVER['PHP_AUTH_USER'] ."</td><td>PHP_AUTH_USER</td></tr>";
echo "<tr><td>" .$_SERVER['PHP_AUTH_PW'] ."</td><td>PHP_AUTH_PW</td></tr>";
echo "<tr><td>" .$_SERVER['AUTH_TYPE'] ."</td><td>AUTH_TYPE</td></tr>";
echo "</table>"
*/
?>
