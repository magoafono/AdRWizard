<?php

include 'config.php';

// These are the smarty files
require('/usr/lib/php5/Smarty/Smarty.class.php');

// This is a file which abstracts the DB connecting functionality (Check out PEAR)
require 'DB.php';

$smarty = new Smarty;

$smarty->compile_check = true;
$smarty->debugging = false;
$smarty->use_sub_dirs = false;
$smarty->caching = false;

//queste 4 righe sono necessarie!!!
$smarty->template_dir = '/var/www/smarty/templates';
$smarty->compile_dir = '/var/www/smarty/templates_c';
$smarty->cache_dir = '/var/www/smarty/cache';
$smarty->config_dir = '/var/www/smarty/configs';


if(!($db = mysql_connect($dbhost, $dbuser, $dbpwd))) //connect to database
        die("Couldn't connect to the database.");
    if(!@mysql_select_db("assegni", $db)) //select database
        die("Database doesn't exist!");


// This SQL statement will get the 5 most recently added new items from the database
$sql = 'SELECT * ';
$sql .= 'FROM assegni where id = $id';
//$sql .= 'ORDER BY id DESC LIMIT 0, 5';

$result = @mysql_query($sql,$db) or die("Query failed : " . mysql_error());

// For each result that we got from the Database
while ($line = mysql_fetch_assoc($result)) {
  $value[] = $line;
 }

// Assign this array to smarty...
$smarty->assign('assegno', $value);

// Display the news page through the news template
$smarty->display('assegno.tpl');

// Thanks to David C James for a code improvement :)
?>
