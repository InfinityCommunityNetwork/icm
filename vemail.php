<?php
//Load config
include 'config.php';

//Make sure the db is alive
if (!$mysqli) {
	die('Could not connect: ' . mysqli_error($mysqli));
}
 
$username=$_GET['user'];

//Select the db
mysqli_select_db($mysqli, $dbname);

//Get the email address
$sql = "SELECT Email FROM `icmdb.Users` WHERE UserName = '" . $username . "'";

$query = $mysqli->query($mysqli, $sql);
$row = $query->fetch_array($query); 
$email = $row['Email'];


//Construct a hash to send as part of the email
$hash=hash('md5',$username . "keelhaul");

//Now send the email
//Change the address in the message and the first header
$headers = "From: topperfalkon@phoeniximperium.org\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
$message = 
"Welcome to the Infinity Corporation Manager.\n
Please <a href=\"" . $siteURL . "/verify.php?user=" . $username . "&hash=" . $hash . "&verified=y\"> click here</a> to finish verification.";
mail($email,"Verify your email address for the Infinity Corporation Manager",$message,$headers);

//Tell the user the email has been sent.
echo 
(
	"A verification email has been sent to your email address. Please open it and follow the link to verify your account.<br />
	<a href=\"index.php\">Click here to return to login page</a>"
);
?>