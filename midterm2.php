
<?php 
require_once 'login.php';
$connection = new mysqli($hn, $un, $pw, $db);
if ($connection->connect_error) die(mysql_fatal_error());
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $un_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_USER']);
        $pw_temp = mysql_entities_fix_string($connection, $_SERVER['PHP_AUTH_PW']);
        $query = "SELECT * FROM users WHERE username='$un_temp'";
        $result = $connection->query($query); 
        if (!$result) die(mysql_fatal_error());
        elseif ($result->num_rows) {
                $row = $result->fetch_array(MYSQLI_NUM);
                $result->close();
                $salt1 = $row[5]; 
                $salt2 = $row[6];
                $token = hash('ripemd128', "$salt1$pw_temp$salt2");
                if ($token == $row[3]) {
                        session_start();
                        $_SESSION['username'] = $un_temp;
                        $_SESSION['password'] = $pw_temp;
                        $_SESSION['forename'] = $row[0];
                        $_SESSION['surname'] = $row[1];
                        echo "$row[0] $row[1] : Hi $row[0], you are now logged in as '$row[2]'";
                        die (header("Location: next.php"));
                }
                else die("Invalid username/password combination");
        }
        else die("Invalid username/password combination");
}
else {
        header('WWW-Authenticate: Basic realm="Restricted Section"');
        header('HTTP/1.0 401 Unauthorized');
        die ("Please enter your username and password");
}
$connection->close();
function mysql_entities_fix_string($connection, $string)
{
        return htmlentities(mysql_fix_string($connection, $string));
}
function mysql_fix_string($connection, $string)
{
        if (get_magic_quotes_gpc()) $string = stripslashes($string);
        return $connection->real_escape_string($string);
}

function mysql_fatal_error(){
echo <<<_END
We are sorry, but it was not possible to completethe requested task. 
Please click the back button on your browserand try again. If you are still having problems,please
<a href="mailto:admin@server.com">emailour administrator</a>. Thank you.
_END;
}

?>

