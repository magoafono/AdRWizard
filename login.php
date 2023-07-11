<?php

include 'config.php';


$db = mysql_connect($dbhost, $dbuser, $dbpwd) or die("Couldn't connect to the database.");
mysql_select_db($dbname) or die("Couldn't select the database");

// Add slashes to the username, and make a md5 checksum of the password.

$num = 0;

if (isset ($_POST['user'])) {
  $_POST['user'] = addslashes($_POST['user']);
  $_POST['pass'] = $_POST['pass'];

  // echo $_POST['user']." + ".$_POST['pass'];
  $result = mysql_query("SELECT count(userId),userRole FROM users WHERE userPass='$_POST[pass]' AND userName='$_POST[user]' group by userName;");/* or die("Couldn't query the user-database.");*/

if (!$result) {
    echo "Could not successfully run query ($sql) from DB: " . mysql_error();
}

  $row = mysql_fetch_assoc($result);
$num = mysql_num_rows($result);
  mysql_free_result($result);
  mysql_close($db);

 }

//echo " num = $num <br>";


if (!$num) {

// When the query didn't return anything,
// display the login form.
  echo "<html>\n";
  echo "<head>\n";
  echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
  echo "<title>Richiesta Bando di concorso (Assegno di Ricerca) on <?php $hostname ?> </title>\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"login.css\" media=\"all\">\n";
  echo "</head>\n";
  echo "<body id=\"main_body\">\n";
 echo "
<div align=center>
<h2>Richiesta Bando di concorso</h2>
<em> (on $hostname)</em>";
 if (isset ($_POST['user']) ) {
   echo '<div style="color:red">Login o Password errate!!</div>';
 }

echo" <table cellpadding=10px >
   <tr>  
    <td >
      <form name=\"login\" id=\"login\" action='$_SERVER[PHP_SELF]' method='post'>
        <table cellpadding=10px cellspacing=10px >
         <tr>  
           <td><label for=\"user\">Username</label></td>
           <td ><input class=\"input2\" type='text' name='user'></td>
        </tr>
        <tr>
           <td ><label for=\"pass\">Password</label></td>
           <td><input class=\"input2\" type='password' name='pass'></td>
        </tr>
        <tr>
           <td colspan=\"2\" align=center> <input type='submit' value='Login'> </td>
        </tr>
      </form>
    </td>
   </tr>
 </table>
</div>
";

 echo "<script type=\"text/javascript\">";
 echo "   document.login.user.focus();";
 echo "</script>";
 echo "</body>";
 echo "</html>\n";
} else {

  if ($maintenance && $_POST['user'] != "simone.marchi") {
    echo '<html>
           <head>
                <meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
                <meta http-equiv="refresh" content="0;URL=maintenance.php">
          </head>
       </html>';

  } else {
    //getting the role
    //  echo "($result), ";
    
    $role = $row['userRole'];
    //  echo " (". $role.")<br>";
    // Start the login session
    session_start();
//    session_register($_POST['user']); 
    // We've already added slashes and MD5'd the password
    $_SESSION['user'] = $_POST['user'];
    $_SESSION['pass'] = $_POST['pass'];
    $_SESSION['role'] = $role;
    
    
    // All output text below this line will be displayed
    // to the users that are authenticated. Since no text
    // has been output yet, you could also use redirect
    // the user to the next page using the header() function.
    // header('Location: page2.php');
    
    echo "<html>\n";
    echo "<head>\n";
    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
    echo "<meta http-equiv=\"refresh\" content=\"2; URL=lista.php\">\n";
    echo "<title>Richiesta Bando di concorso (Assegno di Ricerca)</title>\n";
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"login.css\" media=\"all\">\n";
    echo "</head>\n";
    echo "<body id=\"main_body\">\n";
    echo " <h1>Login effettuato con successo</h1>";
    echo "You're now logged in as (". $_SESSION['user'] .") with role (".$role."). \n Sarete rediretti automaticamente alla <a href='lista.php'>lista delle richieste</a>.";
  }

}

?> 
