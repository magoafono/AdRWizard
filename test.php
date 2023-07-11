<?php


// Create a simple array.
$arr_hh = array("8","9","10","11","12","13","14","15","16","17","18");
$arr_mm = array("00", "15", "30", "45");


?>
<html xmlns="http://www.w3.org/1999/xhtml">
   <head>
   <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Richiesta Bando di concorso (Assegno di Ricerca)</title>
   <link rel="stylesheet" type="text/css" href="view.css" media="all"/>
   <script type="text/javascript" src="view.js"></script>
   <script type="text/javascript" src="calendar.js"></script>
   
   <script type="text/javascript">
   _editor_url  = "/xinha/"  // (preferably absolute) URL (including trailing slash) where Xinha is installed
   _editor_lang = "it"      // And the language we need to use in the editor.
   _editor_skin = "blue-look"   // If you want use a skin, add the name (of the folder) here 
   </script>
  <script type="text/javascript" src="calendar.js"></script>

</head>

<body>
<ul>
  <li style="display=inline">
  <label class="description" for="element_4">Date possibili per il colloquio </label>
  <ul>
  <li >
  <span>
  <input type="radio" name="date_colloquio" value="1" <?if ($date_colloquio==1) {echo "checked=\"checked\"";}?> />
  </span>
  
  <span>
  <input id="element_4_1_1" name="data_colloquio_1_DD" class="element text" size="2" maxlength="2" value="<?echo $data_colloquio_1_DD?>" type="text"/> /
  <label for="element_4_1_1">DD</label>
  </span>

  <span>
  <input id="element_4_1_2" name="data_colloquio_1_MM" class="element text" size="2" maxlength="2" value="<?echo $data_colloquio_1_MM?>" type="text"/> /
  <label for="element_4_1_2">MM</label>
  </span>

  <span>
  <input id="element_4_1_3" name="data_colloquio_1_YYYY" class="element text" size="4" maxlength="4" value="<?echo $data_colloquio_1_YYYY?>" type="text"/>
  <label for="element_4_1_3">YYYY</label>
  </span>

  <span id="calendar_4_1">
  <img id="cal_img_4_1" class="datepicker" src="images/calendar.gif" alt="Pick a date."/>	
  </span>
  <script type="text/javascript">
  Calendar.setup({    
    inputField	 : "element_4_1_3",
	baseField    : "element_4_1",
	displayArea  : "calendar_4_1",
	button		 : "cal_img_4_1",
	ifFormat	 : "%B %e, %Y",
	onSelect	 : selectEuropeDate
	});
</script>

<span id="time_4_1_1">
  <select name="HH_1">
  <?php 
  foreach ($arr_hh as $value) {
    echo "<option value=\"".$value."\"";
    if($value == $hh_colloquio_1) echo " selected=\"selected\"";
    echo ">".$value."</option>\n";
}
?>
</select>
  <label for="element_4_1_1_1">HH</label>
</span>

<span id="time_4_1_2">
<select name="MM_1">
  <?php 
  foreach ($arr_mm as $value) {
    echo "<option value=\"".$value."\"";
    if($value == $mm_colloquio_1) echo " selected=\"selected\"";
       echo ">".$value."</option>\n";
  }
?>
</select>
<label for="element_4_1_1_2">MM</label>
  </span>
</li>
</ul>
</body>

</html>