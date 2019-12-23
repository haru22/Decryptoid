<?php 

require_once 'login.php';
$connection = new mysqli($hn, $un, $pw, $db);
if ($connection->connect_error) 
        die(mysql_fatal_error());

if (isset($_POST['fname']) && isset($_POST['lname']) &&isset($_POST['uname'])&&isset($_POST['uemail']) && isset($_POST['password']))
{

        $forename = get_post($connection, 'fname');
        $surname = get_post($connection, 'lname');
        $username = get_post($connection, 'uname');
        $password = get_post($connection, 'password');
        $email = get_post($connection, 'uemail');
        $salt1 = uniqid();
        $salt2 = uniqid();
        // validate user name
//         username_validation($username);
//         {
//             echo "valid";
//         }else {
//             echo "username is invalid";
//             header("refresh: 3");
//         }
    
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if(preg_match('/[^a-zA-Z0-9_-]/', $username) === 0)
            {       
                if(strlen($password)>=5)
                {
                    $token = hash('ripemd128', "$salt1$password$salt2");
                    add_user($connection, $forename, $surname, $username, $token, $email, $salt1, $salt2);  
                    echo"reach redirect";
                    header("Location: next.php");
                }else
                {
                    echo "password needs to be greater equal than 6";
                    header("refresh: 3");
                }
                
                
            }else
            {
                echo "</br>Invaild Username formate, The username can contain English letters (capitalized or not), digits, and the characters '_' (underscore) and '-' (dash). Nothing else.</br>";
                header("refresh: 3");
            }
        }else 
        {
             echo "Wrong email formate";
             header("refresh: 3");
        }

}
else
{
        echo <<<_END
        <form action="signup.php" method="post"><pre>
        First Name: <input type="text" name="fname">
        Last Name: <input type="text" name="lname">
        User Name: <input type="text" name="uname">
        Email: <input type="email" name="uemail">
        Password: <input type="password" name="password">
        <input type="submit" value="ADD RECORD">
        </pre></form>
_END;
}

$connection->close();

function add_user($connection, $fn, $sn, $un, $pw, $em, $st1, $st2) {
        $query = "INSERT INTO users VALUES('$fn', '$sn', '$un', '$pw','$em','$st1','$st2')";
        $result = $connection->query($query);
        if (!$result) die(mysql_fatal_error());
}
function get_post($connection, $var)
{
        return $connection->real_escape_string($_POST[$var]);
}






function mysql_fatal_error(){
echo <<<_END
We are sorry, but it was not possible to completethe requested task. 
Please click the back button on your browserand try again. If you are still having problems,please
<a href="mailto:admin@server.com">emailour administrator</a>. Thank you.
_END;
}

// function username_validation($input) {
//     $pattern='/[^A-Za-z0-9_-]/'
//     preg_match($pattern,$input,$matches)
//     echo $matches;
// }


?>

