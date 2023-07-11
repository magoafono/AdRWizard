<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

require_once "config.php";
require_once "common.php";
require_once "render-doc.php";
require($mpdf_path.'mpdf.php');

require($smarty_php_path."Smarty.class.php");

// This is a file which abstracts the DB connecting functionality (Check out PEAR)
//require 'DB.php';

$smarty = new Smarty;

$smarty->compile_check = true;
$smarty->debugging = false;
$smarty->use_sub_dirs = false;
$smarty->caching = false;

//queste 4 righe sono necessarie!!!
$smarty->template_dir = $smarty_path.'templates';
$smarty->compile_dir = $smarty_path.'templates_c';
$smarty->cache_dir = $smarty_path.'cache';
$smarty->config_dir = $smarty_path.'configs';

$debug = 0;
if ($debug == 1 )
  var_dump($_POST); 
$id = $_POST['id'];
$id_assegno = $_POST['id_assegno'];
$id_status = $_POST['id_status'];
$anno = $_POST['anno'];
$data_richiesta = $_POST['data_richiesta'];
$data_chiusura = $_POST['data_chiusura'];
$data_servizio = $_POST['data_servizio'];
$membri = $_POST['membri_commissione'];
$data_colloquio = $_POST['data_colloquio'];
$ora_colloquio = $_POST['ora_colloquio'];

$scadenza_bando = $_POST['scadenza_bando'];
$ambito_ricerca = $_POST['ambito_ricerca'];
$tema = $_POST['tema'];
$responsabile = $_POST['responsabile'];
$durata = $_POST['durata'];

$importo = $_POST['importo'];
list($importo_cifre,$importo_lettere) = explode (" ",$importo);

$importo = $importo_cifre.' ('.$importo_lettere.')';

$requisiti_lauree = $_POST['requisiti_lauree'];
$requisiti_esperienze = $_POST['requisiti_esperienze'];
$nome_richiedente = $_POST['nome_richiedente'];

$commessa_id_modulo = $_POST['commessa_id_modulo'];
$commessa_descr_modulo = $_POST['commessa_descr_modulo'];
$commessa_id_comm = $_POST['commessa_id_comm'];
$commessa_descr_comm = $_POST['commessa_descr_comm'];
$commessa_responsabile = $_POST['commessa_responsabile'];

$submit_value = $_POST['submit'];

$template = $_POST['template'];
$template_name = $_POST['template_name'];

$tipo_assegno = $_POST['tipo_assegno'];
$sede = $_POST['sede'];
$fondi_ordinari = $_POST['fondi_ordinari'];

$n_provvedimento = $_POST['n_provvedimento'];
$data_provvedimento = $_POST['data_provvedimento'];

//rimozione dello slash di escaping
$commessa_descr_modulo = stripslashes($commessa_descr_modulo);
$commessa_descr_comm = stripslashes($commessa_descr_comm);
$tema = stripslashes($tema);
$ambito_ricerca = stripslashes($ambito_ricerca);
$requisiti_lauree = stripslashes($requisiti_lauree);
$requisiti_esperienze = stripslashes($requisiti_esperienze);
$membri = stripslashes($membri);


if ($debug==1){
  
  echo "id = $id <br/>";
  echo "data_richiesta = $data_richiesta<br/>";
  echo "data_chiusura = $data_chiusura<br/>";
  echo "data_servizio = $data_servizio<br/>";
  echo "data_colloquio = $data_colloquio<br>";
  echo "membri = $membri<br/>";
  echo "date_colloquio = $date_colloquio<br/>";
  echo "scadenza_bando = $scadenza_bando<br/>";

  echo "ambito_ricerca = $ambito_ricerca<br/>";
  echo "commessa_id_modulo = $commessa_id_modulo<br/>";
  echo "commessa_descr_modulo = $commessa_descr_modulo<br/>";
  echo "commessa_id_comm = $commessa_id_comm<br/>";
  echo "commessa_descr_comm = $commessa_descr_comm<br/>";
  echo "commessa_responsabile = $commessa_responsabile<br/>";
  echo "tema = $tema<br/>";
  echo "responsabile = $responsabile<br/>";
  echo "durata = $durata<br/>";
  echo "importo cifre = ($importo_cifre)<br/>";
  echo "importo lettere = ($importo_lettere)<br/>";
  echo "requisiti_lauree = $requisiti_lauree<br/>";  
  echo "requisiti_esperienze = $requisiti_esperienze<br/>";  
  echo "nome_richiedente = $nome_richiedente<br/>";
  echo "submit_value = $submit_value<br/>";
 }


//metto su 3 cifre l'id_assegno
$id_assegno = sprintf("%03s",$id_assegno);
$anno = sprintf("20%s",$anno);

$smarty->assign('anno',$anno );
$smarty->assign('id_assegno',$id_assegno );
$smarty->assign('id_status',$id_status );
$smarty->assign('data_richiesta', $data_richiesta);
$smarty->assign('data_chiusura', $data_chiusura);
$smarty->assign('data_servizio',$data_servizio );
$smarty->assign('membri',$membri );
//$smarty->assign('data_colloquio',$data_colloquio );
$smarty->assign('data_colloquio',formatta_data($data_colloquio));
$smarty->assign('ora_colloquio',$ora_colloquio );
$smarty->assign('scadenza_bando',formatta_data($scadenza_bando) );
$smarty->assign('ambito_ricerca',$ambito_ricerca );
$smarty->assign('commessa_id_modulo',$commessa_id_modulo );
$smarty->assign('commessa_descr_modulo',$commessa_descr_modulo );
//echo "commessa_id_comm (".$commessa_id_comm.")";
if ($commessa_id_comm != "" ) {
  $smarty->assign('commessa_id_comm',$commessa_id_comm );
 }
$smarty->assign('commessa_descr_comm',$commessa_descr_comm );
$smarty->assign('commessa_responsabile',$commessa_responsabile );
$smarty->assign('tema', htmlspecialchars_decode($tema));
$smarty->assign('responsabile',$responsabile );
$smarty->assign('durata',$durata );
$smarty->assign('importo',$importo );
$smarty->assign('requisiti_lauree',$requisiti_lauree);
$smarty->assign('requisiti_esperienze',$requisiti_esperienze);

$smarty->assign('n_provvedimento',$n_provvedimento);
$smarty->assign('data_provvedimento',$data_provvedimento);

if ($tipo_assegno != NULL){
  $smarty->assign('tipo_assegno', $tipo_assegno);
 }
$smarty->assign('sede',$sede);
$smarty->assign('fondi_ordinari',$fondi_ordinari);

if ($submit_value == "Preview") {
  // Display the news page through the news template
  $smarty->display($template);
}  elseif ($submit_value == "Doc") {
 
  $html = $smarty->fetch($template);
  generaDOC($html);

} else { //PDF
  // echo "5 (" . $allegati_path.")";
  $html = $smarty->fetch($template);
  generaPDF($html, $template_name, $allegati_path, $id_status);
}

function importaAllegati($mp, $allegato){
  $pagecount = $mp->SetSourceFile($allegato);
  for ($i=1; $i <= $pagecount; $i++ ){
    $mp->AddPage();
    $mp->SetHTMLFooter('');
    $tplId = $mp->ImportPage($i);
    $mp->UseTemplate($tplId);
  }
}

function importaAllegatiList($mp, $listOfAllegati) {
//print_r($listOfAllegati);
  foreach ($listOfAllegati as &$all) {
//	echo "all ". $all . "\n";
  	importaAllegati($mp, $all);
  }
}

function generaPDF($html, $template_name,  $allegati_path, $id_status ) {

  $mpdf=new mPDF('utf-8','A4',9,'verdana',25,25,40,15,'','');
  $mpdf->SetImportUse(); 
  
  if ($id_status != 3 ) {
    $mpdf->SetWatermarkText('DRAFT');
    $mpdf->showWatermarkText = true;
  }

$mpdf->SetHTMLHeader('<br/><div style="text-align: center;">
<img src="logo-assegno.jpeg" alt="Intestazione"/> 
</div>
<div style="text-align: center; margin-top:2mm;"><strong>Istituto di Linguistica
    Computazionale "Antonio Zampolli"</strong><br />
</div>
<div style="text-align: center;">
  <small>Via G. Moruzzi, 1 - 56124 Pisa<br />
    Tel. +39 050 3158379, Fax +39 050 3152839<br />
    e-mail: direttore@ilc.cnr.it, http://www.ilc.cnr.it</small><br />
</div>
<hr style="margin-top:2mm;">
');
  $mpdf->SetHTMLFooter('<footer><div style="text-align: right; padding-bottom:30px; padding-right:10px">
{PAGENO} </div>
</footer>');
  $mpdf->WriteHTML($html);

  if ($template_name != "") {
    $allegato = $allegati_path.'allegato'.$template_name;
    $allegatoA = $allegato.'A.pdf';
    $allegatoB = $allegato.'B.pdf';
    $allegatoC = $allegato.'C.pdf';
    $listOfAttachment = array ($allegatoA,$allegatoB);
  }
  if (file_exists($allegatoC)) {
     array_push ($listOfAttachment, $allegatoC);
  }
  // echo "5 (" . $allegati_path.")";

//  importaAllegati($mpdf, $allegatoA);
//  importaAllegati($mpdf, $allegatoB);
    $mpdf->SetHTMLHeader('');
//    $mpdf->SetHTMLFooter('');	
  importaAllegatiList($mpdf,$listOfAttachment);
  $mpdf->Output();
}

?>
