<?php

include 'config.php';

//echo $_POST['user']." <br>";

$db = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Couldn't connect to the database.");
mysql_select_db($dbname) or die("Couldn't select the database");

//echo $_POST['user']." <br>";



$num = 0;
if (isset ($_POST['user'])) {
  // Add slashes to the username, and make a md5 checksum of the password.
  $user = addslashes($_POST['user']);
  
  if ( isset($_POST['errore'])) {
    $err  = $_POST['errore'];
  }
  //echo $_POST['user']." ".$_POST['pass'];
  //echo $user;
  $result = mysql_query("SELECT count(userId),userPass FROM users WHERE userName='$user' group by userName;");
  
  $row = mysql_fetch_assoc($result);
  $num = mysql_result($result, 0, 0);
  $userPass = mysql_result($result, 0, 1);

 }

//echo $_POST['user']." $num <br>";

if ($num != 0) {

  echo "<html>\n";
  echo "<head>\n";
  echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
  echo "<title>Cambio password</title>\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"view.css\" media=\"all\">\n";
  echo "<script type=\"text/javascript\" src=\"view.js\"></script>\n";

  echo "</head>\n";
  echo "<body id=\"main_body\">\n";


  echo "<img id=\"top\" src=\"top.png\" alt=\"\">
     <div id=\"form_container\">
     
     <h1><a>Cambio password</a></h1>
     <form id=\"form_125778\" class=\"appnitro\"  method=\"post\" action=\"save-new-pwd.php\">
     <div class=\"form_description\">
     <h2>Cambio password</h2>
     <p>Immetere una nuova password</p>
     </div>";
  
  if (isset($err)) {
    echo "<label class=\"description\" for=\"error_element\">$err</label><br>";
  }

echo "<ul >
     
       <li id=\"li_1\" >
         <label class=\"description\" for=\"element_1\">Vecchia password </label>
         <div>
          <input id=\"element_1\" name=\"oldpwd\" class=\"element text medium\" type=\"password\" maxlength=\"255\" value=\"\"/> 
         </div> 
      </li>
      <li id=\"li_2\" >
         <label class=\"description\" for=\"element_2\">Nuova password </label>
         <div>
	  <input id=\"element_2\" name=\"newpwd\" class=\"element text medium\" type=\"password\" maxlength=\"255\" value=\"\"/> 
         </div> 
      </li>		
      <li id=\"li_3\" >
         <label class=\"description\" for=\"element_3\">Nuova password (conferma) </label>
	 <div>
	   <input id=\"element_3\" name=\"newpwd2\" class=\"element text medium\" type=\"password\" maxlength=\"255\" value=\"\"/> 
	 </div> 
      </li>
      <li class=\"buttons\">
         <input type=\"hidden\" name=\"form_id\" value=\"125778\" />
         <input type=\"hidden\" name=\"user\" value=\"$user\" />
         <input type=\"hidden\" name=\"pwd\" value=\"$userPass\" />
         <input id=\"saveForm\" class=\"button_text\" type=\"submit\" name=\"submit\" value=\"Ok\" />
      </li>
   </ul>
 </form>\n";
 echo "<table>
  <tr>
    <td>
     <form id=\"form_annulla\" name=\"form_annulla\" class=\"appnitro\"  method=\"post\" action=\"lista.php\" >
       <input type=\"hidden\" id=\"nome_richiedente_annulla\" name=\"nome_richiedente\" value=\"<? echo $user?>\" />
       <input id=\"annulla\" class=\"button_text\" type=\"submit\" name=\"annulla\" value=\"Annulla\" />
     </form>	
    </td>
  </tr>
</table>";


echo " </body>
 </html>";
 }
?>