<?php

function getCalendar ($x) {

  $str = '<span id="calendar_'.$x.'">
  <img id="cal_img_'.$x.'" class="datepicker" src="images/calendar.gif" alt="Pick a date."/>	
     </span>
  <script type="text/javascript">
  Calendar.setup({
        inputField   : "element_'.$x.'_3",
	baseField    : "element_'.$x.'",
	displayArea  : "calendar_'.$x.'",
	button	     : "cal_img_'.$x.'",
	ifFormat     : "%d %m %Y",
        firstDay     : 1,
	onSelect     : selectEuropeDate
	});
</script>';

  return $str;

}

function getTinyMCE() {

if(0) {
   	$tinyDir = '../tinymce/jscripts/tiny_mce/tiny_mce.js'; //tiny = v3
} else {
	$tinyDir = '../tinymce_5.0.14/js/tinymce/tinymce.min.js'; // tiny = v4 o v5
}
print '<script type="text/javascript" src="';
print $tinyDir . '"></script>';

print '<script type="text/javascript">

tinymce.init({
    editor_selector : "mceEditor",
    selector: ".mceEditor",
    menubar: false,
    width : "100%",
    plugins: "code lists advlist paste",
    toolbar: "fontselect fontsizeselect bold italic alignleft aligncenter alignright bullist numlist outdent indent code",
    fontsize_formats: "9pt",
    font_formats: "Verdana",
    advlist_number_styles: "lower-alpha",
    paste_as_text: true
});
</script>';

print '<!--script type="text/javascript">
	tinyMCE.init({
		// General options
// 	        mode : "specific_textareas",
                editor_selector : "mceEditor",
		selector : ".mceEditor",
//		theme : "advanced",
		toolbar: "code undo redo fontfamily fontsize bold italic alignleft aligncenter alignright bullist numlist outdent indent removeformat",
		branding: false,
		menubar: false,
                width : "100%",
		plugins: "code",
		//plugins : "code,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		//theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		//theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		//theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		//theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",

                theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,cut,copy,paste,pastetext,pasteword,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,sub,sup,|,code",
                theme_advanced_buttons2 : "",
                theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "css/custom-content.css",
                theme_advanced_font_sizes: "10px,12px,13px,14px,16px,18px,20px",
                font_size_style_values : "10px,12px,13px,14px,16px,18px,20px",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Style formats
		style_formats : [
			{title : \'Bold text\', inline : \'b\'},
			{title : \'Red text\', inline : \'span\', styles : {color : \'#ff0000\'}},
			{title : \'Red header\', block :\'h1\', styles : {color : \'#ff0000\'}},
			{title : \'Example 1\', inline : \'span\', classes : \'example1\'},
			{title : \'Example 2\', inline : \'span\', classes : \'example2\'},
			{title : \'Table styles\'},
			{title : \'Table row 1\', selector : \'tr\', classes : \'tablerow1\'}
		],

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script-->
';
    }

function getDataRichiesta($id, $data_richiesta){
  $str = ' <li id="li_'.$id.'" >
  <span>
  <label class="description" for="element_'.$id.'">Data della richiesta </label>
  <input id="element_'.$id.'_1" name="data_richiesta" class="element text" size="10" maxlength="10" type="text" readonly="readonly" value="'.$data_richiesta.'"/>
  <p class="guidelines" id="guide_'.$id.'"><small>Data della richiesta del bando di concorso <em>(non modificabile)</em></small></p> 
  </span>
  </li>';
  return $str;
}

function getIdAssegno($id, $id_assegno, $role){
  $str = ' <li id="li_'.$id.'" >
  <span>
  <label class="description" for="element_'.$id.'">Numero Assegno</label>
  <input id="element_'.$id.'_1" name="id_assegno" class="element text" size="10" maxlength="10" type="text" ' . ($role != "admin"? 'readonly = "true"':'') . '  value="'.$id_assegno.'"/>
  <p class="guidelines" id="guide_'.$id.'"><small>Numero dell\'assegno (ILC.ASS.XXX.2022.PI)</small></p>
  </span>
  </li>';
  return $str;
}


function getSedeLavoro($id, $sede, $arr_sede) {

  $str = "";

  if ($sede != 0 ) {
    $str = '
<li id="li_'.$id.'" > 
  <span>
  <label class="description" for="element_'.$id.'">Sede di lavoro </label>

  <select class="element select large" id="element_'.$id.'" name="sede">';
    for ($i = 0 ; $i < sizeof($arr_sede); $i++) {
      $value = $arr_sede[$i];
      
      $str .= '<option value="'.$value['id_sede'].'"';
      if($i+1 == $sede) 
	$str .= ' selected="selected"';
      $str .= '>'.$value['luogo'].'</option>\n';
    }
    $str .= '</select>';
  }
  $str .= '<p class="guidelines" id="guide_'.$id.'"><small>Sede di lavoro</small></p> 
  </span>
  </li>';

  return  $str;

}

function getTipologiaAssegno($id, $arr_id_tipo_assegno, $id_tipo_assegno) {
  $str = '
  <li id="li_'.$id.'">
     <div>
     <label class="description" for="element_'.$id.'">Tipologia di assegno</label>
     <select class="element select large" id="element_'.$id.'" name="id_tipo_assegno">';
  
  for ($i = 0 ; $i < sizeof($arr_id_tipo_assegno); $i++) {
    $value = $arr_id_tipo_assegno[$i];
    $str .= '<option title="'.$value['nome'].' valore minimo €'.$value['min_value'].'" value="'.$value['id_tipo_assegno'].'"';
    if($i+1 == $id_tipo_assegno) $str .= ' selected="selected"';
    $str .= '>'.$value['nome'].' - €'.$value['min_value'].'</option>\n';
  }
  $str .=' </select>
  </div>  
       <p class="guidelines" id="guide_1a"><small>Specificare il tipo di assegno in base al profilo desiderato</small></p> 

  </li>';
  return  $str;
}

function getPresaServizio($id, $presa_servizio_DD, $presa_servizio_MM, $presa_servizio_YYYY) {
  $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_'.$id.'">Indicazione presa di servizio</label>';

  $str .= '<span>
  <input id="element_'.$id.'_1" name="presa_servizio_DD" class="element text" size="2" maxlength="2" value="'.$presa_servizio_DD.'" type="text"/> /
  <label for="element_'.$id.'_1">DD</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_2" name="presa_servizio_MM" class="element text" size="2" maxlength="2" value="'. $presa_servizio_MM.'" type="text"/> /
  <label for="element_'.$id.'_2">MM</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_3" name="presa_servizio_YYYY" class="element text" size="4" maxlength="4" value="'.$presa_servizio_YYYY.'" type="text"/>
  <label for="element_'.$id.'_3">YYYY</label>
  </span>';
  
  $str.= getCalendar($id);

  $str .= '<p class="guidelines" id="guide_'.$id.'"><small>Proposta inizio attività: solitamente inizio del mese</small></p> 
  </li>	';	
  return  $str;
}

function getMembriCommissione($id, $membri_commissione) {
  $str = '<li id="li_'.$id.'" >';
  $str .= '<label class="description" for="element_'.$id.'">Membri della commisione </label>';
  $str .= '<div>
  <textarea id="element_'.$id.'" name="membri" class="element_textarea_medium" cols="40" rows="6">'. $membri_commissione.'</textarea>';
  $str .= '</div><p class="guidelines" id="guide_3"><small>Responsabile della ricerca + 2 ricercatori esperti della materia + 2 supplenti (interni o esterni)</small></p>
  </li>';
  return  $str;
}

function getDataColloquio($id, $data_colloquio_1_DD, $data_colloquio_1_MM, $data_colloquio_1_YYYY,$ora_colloquio_1) {

 $str = '<li id="li_'.$id.'">
  <label class="description" for="element_'.$id.'">Data del colloquio </label>
  <ul>';
  
 # $str .= simpleDataColloquio ($id,"1", $date_colloquio, $data_colloquio_1_DD, $data_colloquio_1_MM, $data_colloquio_1_YYYY, $ora_colloquio_1);
  $str .= dataColloquio ($id,"1", "1", $data_colloquio_1_DD, $data_colloquio_1_MM, $data_colloquio_1_YYYY, $ora_colloquio_1);
 
  $str .= '</ul>
<p class="guidelines" id="guide_'.$id.'"><small>Si raccomanda di tener conto della tempistica necessaria per la pubblicazione su URP, scadenza bando +15gg, colloquio +7gg, tempi per la stipula del contratto e denuncia alla Provincia [in giorni lavorativi]</small></p> 
  </li>';	
 
  return  $str;
}

function getDatePossibili($id, $date_colloquio,$data_colloquio_1_DD, $data_colloquio_1_MM, $data_colloquio_1_YYYY,
			  $data_colloquio_2_DD, $data_colloquio_2_MM, $data_colloquio_2_YYYY,
			  $data_colloquio_3_DD, $data_colloquio_3_MM, $data_colloquio_3_YYYY,
			  $ora_colloquio_1, $ora_colloquio_2, $ora_colloquio_3
			  ) {

  $str = '<li id="li_'.$id.'">
  <label class="description" for="element_'.$id.'">Date possibili per il colloquio </label>
  <ul>';
  
  $str .= dataColloquio ($id,"1", $date_colloquio, $data_colloquio_1_DD, $data_colloquio_1_MM, $data_colloquio_1_YYYY, $ora_colloquio_1);
  $str .= dataColloquio ($id,"2", $date_colloquio, $data_colloquio_2_DD, $data_colloquio_2_MM, $data_colloquio_2_YYYY, $ora_colloquio_2);
  $str .= dataColloquio ($id,"3", $date_colloquio, $data_colloquio_3_DD, $data_colloquio_3_MM, $data_colloquio_3_YYYY, $ora_colloquio_3);
  
 
  $str .= '</ul>
<p class="guidelines" id="guide_'.$id.'"><small>Si raccomanda di tener conto della tempistica necessaria per la pubblicazione su URP, scadenza bando +15gg, colloquio +7gg, tempi per la stipula del contratto e denuncia alla Provincia [in giorni lavorativi]</small></p> 
  </li>';	
 
  return  $str;

}

function simpleDataColloquio ($id,$n, $date_colloquio, $dd, $mm, $yyyy, $ora_colloquio) {

  $str = '<span>
  <input id="element_'.$id.'_'.$n.'_1" name="data_colloquio_'.$n.'_DD" class="element text" size="2" maxlength="2" value="'.$dd.'" type="text"/> /
  <label for="element_'.$id.'_'.$n.'_1">DD</label>
  </span>';

  $str .='<span>
  <input id="element_'.$id.'_'.$n.'_2" name="data_colloquio_'.$n.'_MM" class="element text" size="2" maxlength="2" value="'.$mm.'" type="text"/> /
  <label for="element_'.$id.'_'.$n.'_2">MM</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_'.$n.'_3" name="data_colloquio_'.$n.'_YYYY" class="element text" size="4" maxlength="4" value="'. $yyyy.'" type="text"/>
  <label for="element_'.$id.'_'.$n.'_3">YYYY</label>
  </span>';

  $str .= getCalendar($id.'_'.$n);

  $str .= '<span id="time_'.$id.'_'.$n.'_1">
  <select name="HH_'.$n.'">';
  for($hours=8; $hours<18; $hours++) // the interval for hours is '1'
  	  for($mins=0; $mins<60; $mins+=15) // the interval for mins is '30'
              $str .='<option>'.str_pad($hours,2,'0',STR_PAD_LEFT).':'
	                       .str_pad($mins,2,'0',STR_PAD_LEFT).'</option>';
  $str .='</select>
<label for="element_4_'.$id.'_'.$n.'_1">Orario</label>
  </span>';

return $str;

}

function dataColloquio ($id,$n, $date_colloquio, $dd, $mm, $yyyy, $ora_colloquio) {
  //console_log("ora_colloquio: " . $ora_colloquio);
  $str = '<li>
  <span>
  <input type="radio" name="date_colloquio" value="'.$n.'"';

  if ($date_colloquio==$n) {
    $str.= ' checked="checked"/>';
  } else {
    $str .= '/>';
  }
  $str .= '</span>';
  $str .= '<span>
  <input id="element_'.$id.'_'.$n.'_1" name="data_colloquio_'.$n.'_DD" class="element text" size="2" maxlength="2" value="'.$dd.'" type="text"/> /
  <label for="element_'.$id.'_'.$n.'_1">DD</label>
  </span>';

  $str .='<span>
  <input id="element_'.$id.'_'.$n.'_2" name="data_colloquio_'.$n.'_MM" class="element text" size="2" maxlength="2" value="'.$mm.'" type="text"/> /
  <label for="element_'.$id.'_'.$n.'_2">MM</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_'.$n.'_3" name="data_colloquio_'.$n.'_YYYY" class="element text" size="4" maxlength="4" value="'. $yyyy.'" type="text"/>
  <label for="element_'.$id.'_'.$n.'_3">YYYY</label>
  </span>';

  $str .= getCalendar($id.'_'.$n);

  $str .= '<span id="time_'.$id.'_'.$n.'_1">
<select name="HH_'.$n.'">';
/*
  $arr_hh = array("8:00","8:30","9:00","9:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30","17:00","17:30","18:00","18:30");
  if ($arr_hh != ""){
    foreach ($arr_hh as $value) {
      $str .= '<option value="'.$value.'"';
      if($value == $ora_colloquio) $str .= ' selected="selected"';
      $str .= '>'.$value.'</option>\n';
    }
  }
*/

for($hours=8; $hours<18; $hours++) {// the interval for hours is '1'
    for($mins=0; $mins<60; $mins+=15) {// the interval for mins is '30'
    	$time = str_pad($hours,2,'0',STR_PAD_LEFT) . ":" . str_pad($mins,2,'0',STR_PAD_LEFT);
    	//console_log(">> " . $time);
	if($time == $ora_colloquio)  {
          $str .='<option value='.$time.' selected>'.$time.'</option>';
	} else {
          $str .='<option value='.$time.'>'.$time.'</option>';
	}
    }
}
$str .='</select>
<label for="element_4_'.$id.'_'.$n.'_1">Orario</label>
  </span></li>';

return $str;
}

function getScadenzaBando($id, $dd, $mm, $yyyy){

  $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_'.$id.'">Scadenza bando </label>
  <span>';
  $str .= '<input id="element_'.$id.'_1" name="scadenza_bando_DD" class="element text" size="2" maxlength="2" value="'.$dd.'" type="text"/> /
  <label for="element_'.$id.'_1">DD</label>
  </span>
  <span>
  <input id="element_'.$id.'_2" name="scadenza_bando_MM" class="element text" size="2" maxlength="2" value="'.$mm.'" type="text"/> /
  <label for="element_'.$id.'_2">MM</label>
  </span>
  <span>
  <input id="element_'.$id.'_3" name="scadenza_bando_YYYY" class="element text" size="4" maxlength="4" value="'.$yyyy.'" type="text"/>
  <label for="element_'.$id.'_3">YYYY</label>
     </span>';
     
  
  $str .= getCalendar($id);
  $str .= '<p class="guidelines" id="guide_'.$id.'"><small>Data di scadenda del bando</small></p> 
     </li>';	

  return $str;
}

function getNomeEnteErogatore ($id, $ambito_ricerca) {

  $str ='<li id="li_'.$id.'" >
     <label class="description" for="element_6">Nome del progetto di ricerca ed Ente erogatore</label>
     <div>

<textarea id="element_'.$id.'" name="ambito_ricerca" class="element_textarea_small" cols="40" rows="10">'.$ambito_ricerca.'</textarea> 
     </div><p class="guidelines" id="guide_'.$id.'"><small>Indicare il titolo del progetto di ricerca da riportare nell\'intestazione del bando e dell\'Ente erogatore.</small></p> 
     </li>';		

  return  $str;
}

function getCoperturaFinanziaria ($id, $arr_commesse, $commessa_modulo) {

  $str = '<li id="li_'.$id.'" >
     <label class="description" for="element_'.$id.'">Copertura finanziaria Commessa/Modulo </label>
     <div>
     <select class="element select large" id="element_'.$id.'" name="commessa_modulo">';

  for ($i = 0 ; $i < sizeof($arr_commesse); $i++) {
    $value = $arr_commesse[$i];
    $str .= $i." ".$value['descr_modulo'];
    $str .= ' <option title="'.$value['descr_modulo']. ' (Responsabile '.$value['responsabile'].')" value="'.$value['id'].'"';
    if($i+1 == $commessa_modulo) 
      $str .= ' selected="selected"';
    $str .= ">".$value['id_modulo']." ".$value['descr_modulo']." (Responsabile ".$value['responsabile'].")</option>\n";
  }

  $str .= '</select>
       </div><p class="guidelines" id="guide_'.$id.'"><small>Indicare il progetto di ricerca e la commessa/modulo per la copertura finanziaria (vedi Allegato B)</small></p> 
       </li>';
  
  return $str;
}

function getTema ($id, $tema) {

  $str = '<li id="li_'.$id.'" >
       <label class="description" for="element_8">Tema</label>
     <div>
     <textarea id="element_'.$id.'" name="tema" class="element textarea medium"  cols="40" rows="6">'.$tema.'</textarea> 
     </div><p class="guidelines" id="guide_'.$id.'"><small>Tematica oggetto della selezione (art. 1 del bando)</small></p> 
     </li>';		
  return $str;
}

function getResponsabile($id, $responsabile) {

  $str ='<li id="li_'.$id.'" >
     <label class="description" for="element_'.$id.'">Responsabile Scientifico </label>
     <div>
     <input id="element_'.$id.'" name="responsabile" class="element text large" type="text" maxlength="255" value="'.$responsabile.'"/> 
     </div> 
     </li>';
  return $str;
}

function getDurata ($id, $durata) {
  $str = '<li id="li_'.$id.'" >
     <label class="description" for="element_10">Durata (mesi)</label>
     <div>
     <input id="element_'.$id.'" name="durata" class="element_text_medium" type="text" pattern="\d+" maxlength="255" value="'.$durata.'"/> 
  </div><p class="guidelines" id="guide_'.$id.'"><small>La durata espressa in mesi non può essere inferiore ad un anno (12) </small></p> 
     </li>';
  return $str;
}

function getImporto ($id, $importo_cifre, $importo_lettere) {
  $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_11">Importo</label>
  <span>
  <input id="element_'.$id.'" name="importo_cifre"  type="element text" size="12" value="'.$importo_cifre.'" pattern="\d\d\.\d\d\d,\d\d"/> 
  <label>Numeri</label>
  </span>';

  $str .= '<span>
  (<input id="element_'.$id.'a" name="importo_lettere" type="element text" size="32" value="'. $importo_lettere.'" />)
  <label>Lettere</label>
  </span>';

  $str .= '<div class="guidelines" id="guide_'.$id.'">
  <small>Indicare l\'importo per l\'intera durata: <br/> 
  Professionalizzante: 
  <ul><li>€19.367,00</li>
  <li>€20.500,00</li>
  <li>€22.000,00</li>
  <li>€24.000,00</li>
  </ul><br/>
  Post-Doc:   
  <ul>  
  <li>€22.000,00</li>
  <li>€24.000,00</li>
  <li>€26.000,00</li>
  <li>€28.000,00</li>
  </ul><br/>
  Senior:   <ul>  
  <li>€26.000,00</li>
  <li>€28.000,00</li>
  <li>€30.000,00</li>
  <li>€32.000,00</li>
  </ul></small></div> 
';
  return $str;
}


function getFondiByRole ($id, $role, $fondi_ordinari) {

  if ($role == "admin") { 
 
    $str = '<span>
        <label class="description" for="element_'.$id.'">Fondi Ordinari</label>
        <fieldset>
           <label style="display:inline" for="fondi_si">Sì<input type="radio" id="fondi_si" name="fondi_ordinari" value="1" ';
  if (strcmp($fondi_ordinari,"1")==0) 
    $str .= ' checked="checked" ';
  $str .= '/></label>
           <label style="display:inline" for="fondi_no">No<input type="radio" id="fondi_no" name="fondi_ordinari" value="0" ';
  if (strcmp($fondi_ordinari,"0")==0) 
    $str .= ' checked="checked" ';
  $str .= '/></label>
        </fieldset>
        </span>';
  } else {
    $str = '<input type="hidden" name="fondi_ordinari" value="'.$fondi_ordinari.'" />';
  }

  return $str;

}

function getLauree($id, $requisiti_lauree, $requisiti_lauree_def) {
  
  $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_12">Lauree richieste</label>
  <div>
<textarea id="mcelauree" name="requisiti_lauree" class="mceEditor" rows="20" cols="80" style="width: 80%">';
  if ($requisiti_lauree!="") {
    $str .= $requisiti_lauree;
  } else { 
    $str .= $requisiti_lauree_def;
  } 
  $str .= '</textarea> 
</div><p class="guidelines" id="guide_'.$id.'"><small>Art. 3 del bando: Laurea vecchio ordinamento, Laurea Specialistica nuovo ordinamento (indicare anche la classe), esperienze/conoscenze, competenze informatiche, conoscenza lingue.
  </small></p> 
  </li>';

  return $str;
}


function getEsperienze($id, $requisiti_esperienze,$requisiti_esperienze_def, $isUpdate ) {

  $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_12b">Esperienze richieste</label>
  <div>
  <textarea id="mcerequisiti" name="requisiti_esperienze" class="mceEditor" rows="20" cols="80" style="width: 80%">';
//  if ($requisiti_esperienze != "") {
  if ($isUpdate) {
    $str .= $requisiti_esperienze;
  } else { 
    $str .= $requisiti_esperienze_def;
  } 

  $str .= '</textarea> 
</div><p class="guidelines" id="guide_'.$id.'"><small>Art. 3 del bando: Laurea vecchio ordinamento, Laurea Specialistica nuovo ordinamento (indicare anche la classe), esperienze/conoscenze, competenze informatiche, conoscenza lingue.
  </small></p> 
  </li>';

  return $str;
}

function getNomeRichiedente ($id, $nome_richiedente_esteso) {

  $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_'.$id.'">Nome del richiedente </label>
  <div>
  <input id="element_'.$id.'" name="nome_richiedente_esteso" readonly="readonly" class="element text medium" type="text" maxlength="255" value="'.$nome_richiedente_esteso.'"/> 
  </div> 
  </li>';
 
  return $str;
}

function getProvvedimento ($id, $role, $n_provvedimento, $data_provvedimento_DD, $data_provvedimento_MM, $data_provvedimento_YYYY) {

   $str = '<li id="li_'.$id.'" >
  <label class="description" for="element_'.$id.'">Determinazione a contrarre</label>
  <span>
  <input id="element_'.$id.'a" name="n_provvedimento" '. ($role != "admin"? 'readonly = "true"':'').' type="element text" size="6" value="'.$n_provvedimento.'"/> 
  <label>Numero</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_1" name="data_provvedimento_DD" '. ($role != "admin"? 'readonly = "true"':'').' class="element text" size="2" maxlength="2" value="'.$data_provvedimento_DD.'" type="text"/> /
  <label for="element_'.$id.'_1">Giorno</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_2" name="data_provvedimento_MM" '. ($role != "admin"? 'readonly = "true"':'').' class="element text" size="2" maxlength="2" value="'. $data_provvedimento_MM.'" type="text"/> /
  <label for="element_'.$id.'_2">Mese</label>
  </span>';

  $str .= '<span>
  <input id="element_'.$id.'_3" name="data_provvedimento_YYYY" '. ($role != "admin"? 'readonly = "true"':'').' class="element text" size="4" maxlength="4" value="'.$data_provvedimento_YYYY.'" type="text"/>
  <label for="element_'.$id.'_3">Anno</label>
  </span>';
  
  if ($role == "admin")
    $str .= getCalendar($id);

  return $str;
    
}

function console_log($output, $with_script_tags = true) {
    $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
    if ($with_script_tags) {
        $js_code = '<script>' . $js_code . '</script>';
    }
    echo $js_code;
}

function formatta_data($date_str) {
   $parts = explode('-',$date_str);
   $day = $parts[0];
   $month = (int) $parts[1];
   $year = $parts[2];

   $months = array("gennaio", "febbraio", "marzo", "aprile" ,"maggio", "giugno", "luglio", "agosto", "settembre", "ottobre", "novembre", "dicembre");

   return $day . " " . $months[$month - 1] . " " . $year;
}

?>
