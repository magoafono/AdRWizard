<?php
$dbhost = 'db';
$dbport = '';
$dbname = 'DBNAME';
$dbuser = 'USER';
$dbpwd  = 'PASSWORD';

$smarty_php_path = 'INSERIRE IL PATH A SMARTY';
$smarty_path = '../smarty/';
$mpdf_path = '../mpdf/';
$allegati_path = 'allegati/';
$phpdocx_path = '../phpdocx_free';

$sendEmail = true;
//$sendEmail = false;
//sito in manutenzio
$maintenance = false;
$hostname = gethostname();

if (!function_exists('htmlspecialchars_decode')) {
  
  if (!function_exists("htmlspecialchars_decode")) {
    function htmlspecialchars_decode($string, $quote_style = ENT_COMPAT) {
      return strtr($string, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
    }
  }
 }

if (!function_exists('session_is_registered')) {
	function session_is_registered($arg_1){
	    return isset( $_SESSION['user'] );
	}
}

?>
