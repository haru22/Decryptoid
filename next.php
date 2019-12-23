<?php
require_once 'login.php';

$connection = new mysqli($hn, $un, $pw, $db);
if ($connection->connect_error) 
	die(mysql_fatal_error());

session_start();

if (isset($_SESSION['username'])){
	$username = $_SESSION['username'];
	$password = $_SESSION['password'];
	$forename = $_SESSION['forename'];
	$surname = $_SESSION['surname'];

	echo "Welcome back $forename.<br>
	Your full name is $forename $surname.<br>
	Your username is '$username' and your password is '$password'.
    </br>";
    
    destroy_session_and_data();
    

	
	if (isset($_POST['filename'])&&isset($_FILES['textfile']))
{
	$name = mysql_entities_fix_string($connection, $_POST['filename']);
	$content = mysql_entities_fix_string($connection, file_get_contents($_FILES['textfile']['tmp_name'], FALSE, NULL, 0, 20));
	$type = $_FILES['textfile']['type'];
	if (empty($name)) {
        echo "Needed a name";
    }else if($type != 'text/plain'){
    	echo "Only supporting txt files";
    }else
    {
        $query = "INSERT INTO texts (username,name,content) VALUES ('$username','$name', '$content')";
        $result = $connection->query($query);
        if (!$result) die (mysql_fatal_error());
    }
}
    
    
    if (isset($_POST['filename_m'])&&isset($_POST['textfile_m']))
{
	$name_m = mysql_entities_fix_string($connection, $_POST['filename_m']);
	$content_m = mysql_entities_fix_string($connection, $_POST['textfile_m']);
	if (empty($name_m)) {
        echo "Needed a name";
    }else if(empty($content_m)){
    	echo "Empty Entry not Allowed";
    }else
    {
        $query = "INSERT INTO texts (username,name,content) VALUES ('$username','$name_m', '$content_m')";
        $result = $connection->query($query);
        if (!$result) die (mysql_fatal_error());
    }
}
    
    if (isset($_POST['file_selected']) && isset($_POST['algorithm'])&& isset($_POST['type']))
{
        echo "</br></br>";
        $file = mysql_entities_fix_string($connection, $_POST['file_selected']);
        $alg = mysql_entities_fix_string($connection, $_POST['algorithm']);
        $types = mysql_entities_fix_string($connection, $_POST['type']);

        $query = "SELECT * FROM texts WHERE name = '$file' AND username = '$username'";
        $result = $connection->query($query);
        if (!$result) die (mysql_fatal_error());
        $row = $result->fetch_array(MYSQLI_NUM);
        $resultout = encdechelper($alg, $types, $row[2]);
        
        if($resultout)
        {
            $algr = algrtype($alg);
            
            if($types===ec)
            {
                $typestr = "Encryption";
            }else
            {
                $typestr = "Decryption";
            }
       
        
            $query2 = "INSERT INTO operations (username,contentsfrom,contentsto,algr,type) VALUES ('$username','$row[2]', '$resultout','$algr','$typestr')";
            $result = $connection->query($query2);
            if (!$result) die (mysql_fatal_error());
        }
        
    
}

echo <<<_END
		<form action="" method="POST" enctype="multipart/form-data">
            </br> </br>
			File Name: <input type="text" name="filename"><br/>
            <input type="file" name="textfile" />
            <input type="submit"/>
            
        
        </form>
        
        <form action="next.php" method="post">
            </br> </br>
			File Name: <input type="text" name="filename_m"><br/>
            Contents: <input type="text" name="textfile_m"><br/>
            <input type="submit"/>
            
        
        </form>
        
        <form action="" method="POST" enctype="multipart/form-data">
			
            </br> </br>
            
            Input the file name that you want to operate on: 
            </br>
            <input type="text" name="file_selected">
            
            <select name="algorithm">
            <option value="ss">Simple Substitution</option>
            <option value="dd">Double Transposition</option>
            <option value="rc4">RC4</option>
            <option value="des">DES</option>
            </select>
            
            
            <select name="type">
            <option value="ec">Encryption</option>
            <option value="dc">Decryption</option>
            </select>
            
            <input type="submit"/>
        </form>
_END;

$query = "SELECT * FROM texts WHERE username = '$username'";
$result = $connection->query($query);
if (!$result) die (mysql_fatal_error());
$rows = $result->num_rows;
echo "Files List:";
for ($j = 0 ; $j < $rows ; ++$j)
{
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
		echo <<<_END
	<pre>
--------------------------------------
File Name: $row[1]
--------------------------------------
CONTENT: $row[2]</br>--------------------------------------
TimeStamp Submitted: $row[3]
--------------------------------------
    </br>
	</pre>
    
_END;
}
    
$query = "SELECT * FROM operations WHERE username = '$username'";
$result = $connection->query($query);
if (!$result) die (mysql_fatal_error());
$rows = $result->num_rows;
echo "Operations List";
for ($j = 0 ; $j < $rows ; ++$j)
{
	$result->data_seek($j);
	$row = $result->fetch_array(MYSQLI_NUM);
		echo <<<_END
	<pre>
--------------------------------------
Algorithm: $row[3]
--------------------------------------
Type: $row[4]
--------------------------------------
Contents From: $row[1]</br>--------------------------------------
Contents To: $row[2]</br>--------------------------------------
TimeStamp Submitted: $row[5]
--------------------------------------
	</pre>
_END;
}
    
    
}

else{

echo "<a href='final.php'>Click here</a> to log in.<br/>";
echo "<a href='signup.php'>Click here</a> to sign up.";
}
$connection->close();

function encdechelper($algri, $type, $fileselctedcon)
{
    
    if(!$fileselctedcon)
    {
        echo "File selected not exsist or empty";
    }
    else
    {
        
        if($type===ec)
        {
            $typeboolean = TRUE;
            $typestr = "Encryption";
        }else
        {
            $typeboolean = FALSE;
            $typestr = "Decryption";
        }
        
        if($algri==="ss") {
            
        return ss_do_stuff($fileselctedcon,$typeboolean);
        
        }
        
        if($algri==="dd") {
        return dd_do_stuff($fileselctedcon,$typeboolean);    
            
        }
        
        if($algri==="rc4") {
        return rc4_do_stuff($fileselctedcon,$typeboolean);    
            
        }
        if($algri==="des") {
        return des_do_stuff($fileselctedcon,$typeboolean);    
            
        }
            
     

    
}}

function algrtype($algri){
    if($algri==="ss") {
            
        return "Simple Substitution" ;
        
        }
        
        if($algri==="dd") {
        return "Double Substitution";   
            
        }
        
        if($algri==="rc4") {
        return "RC4";    
            
        }
        if($algri==="des") {
        return "DES";    
            
        }
}


function ss_do_stuff($filecontents,$endc)
{
    if($endc)
    {
        $oldAlphabet = "abcdefghijklmnopqrstuvwxyz";
        $newAlphabet = "ynicgszxfrpmauvjtewkbhlqod";
    }else
    {
        $oldAlphabet = "ynicgszxfrpmauvjtewkbhlqod";
        $newAlphabet = "abcdefghijklmnopqrstuvwxyz";
    }
    
    $output = "";
	$inputLen = strlen($filecontents);
    

	for ($i = 0; $i < $inputLen; ++$i)
	{
		$oldCharIndex = strpos($oldAlphabet, strtolower($filecontents[$i]));

		if ($oldCharIndex !== false)
			$output .= ctype_upper($filecontents[$i]) ? strtoupper($newAlphabet[$oldCharIndex]) : $newAlphabet[$oldCharIndex];
		else
			$output .= $filecontents[$i];
	}
    echo $output;
    return $output;
}





function dd_do_stuff($filecontents,$endc)
{
    
    $strippedfile = str_replace(' ', '', $filecontents);
   
    if($endc)
    {
        exec("java ddecj \"$strippedfile\"", $output);
        echo $output[0];
        return $output[0];
    }else
    {
        exec("java dddc \"$strippedfile\"", $output);
        echo $output[0];
        return $output[0];
    }
    
}

function rc4_do_stuff($filecontents,$endc)
{
    $strippedfile = str_replace(' ', '', $filecontents);
     if($endc)
    {

     exec("java rc4 \"$strippedfile\" true", $output);
     echo $output[0];
     return $output[0];
     
    }else
    {
     exec("java rc4 \"$strippedfile\" false", $output);
     echo $output[0];
     return $output[0];
    }

}

function des_do_stuff($filecontents,$endc)
{
    $strippedfile = str_replace(' ', '', $filecontents);
    if($endc)
    {
     exec("java des \"$strippedfile\" true", $output);
     echo $output[0];
     return $output[0];
     
    }else
    {
     exec("java des \"$strippedfile\" false", $output);
     echo $output[0];
     return $output[0];
    }
    
}
function mysql_entities_fix_string($connection, $string)
{
	return htmlentities(mysql_fix_string($connection, $string));
}
function mysql_fix_string($connection, $string)
{
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return $connection->real_escape_string($string);
}
function destroy_session_and_data() 
{
        $_SESSION = array();
        setcookie(session_name(), '', time() - 2592000, '/');
        session_destroy();
}
function mysql_fatal_error(){
echo <<<_END
</br>
We are sorry, but it was not possible to completethe requested task. 
Please click the back button on your browserand try again. If you are still having problems,please
<a href="mailto:admin@server.com">emailour administrator</a>. Thank you.
_END;
}


?>
