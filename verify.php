<?php
//Load config
include 'config.php';

session_start();

//Make sure the db is alive
if (!$mysqli)
  {
  die('Could not connect: ' . mysqli_error($mysqli));
  }

//Variables
$username=$_GET['user'];
$verified=$_GET['verified'];
	//The email link gives us the hash
$hash=$_GET['hash'];
	//Use MD5 and the username to reconstruct the hash, to prevent false verification
$hash0=hash('md5',$username . "keelhaul");
$comp=strcmp($hash , $hash0);

if($comp != 0) {
	echo "You've done something wrong, please <a href=\"index.php\">go back</a>.<br />";

} else {
	//check that people aren't being naughty and leaving out parameters
	if(!strlen($username) > 0) {
		echo "You've done something wrong, please <a href=\"index.php\">go back</a>.";
		die();
	}

	if(!strlen($verified) > 0) {
		echo "You've done something wrong, please <a href=\"index.php\">go back</a>.";
		die();
	}

	//select the db
	mysqli_select_db($mysqli, $dbname);

	$sql = "UPDATE `icmdb.Users` 
	SET EmailVerified = 1 
	where UserName = '" . $username . "'";

	if (!mysqli_query($mysqli, $sql))
			{
			die('Error: ' . mysqli_error($mysqli));
			}
	header( 'Location: '. $siteURL .'/index.php?v=1'); //take us back to the index with the verified parameter set
}
?>