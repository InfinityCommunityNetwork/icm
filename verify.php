<?php
//Load config
include 'config.php';

session_start();

//make sure the db is alive
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

//declare variables
$username=$_GET['user'];
$verified=$_GET['verified'];
	//The email link gives us the hash
$hash=$_GET['hash'];
	//Use MD5 and the username to reconstruct the hash, to prevent false verification
$hash0=hash('md5',$username . "keelhaul");
$comp=strcmp($hash , $hash0);

if($comp != 0)
{
	echo "You've done something wrong, please <a href=\"index.php\">go back</a>.<br />";
}

else
{
	//check that people aren't being naughty and leaving out parameters
	if(!strlen($username) > 0)
	{
		echo "You've done something wrong, please <a href=\"index.php\">go back</a>.";
		die();
	}

	if(!strlen($verified) > 0)
	{
		echo "You've done something wrong, please <a href=\"index.php\">go back</a>.";
		die();
	}

	//select the db
	mysql_select_db($dbname);

	$sql = "UPDATE test_users 
	SET EmailVerified = 1 
	where UserName = '" . $username . "'";

	if (!mysql_query($sql))
			{
			die('Error: ' . mysql_error());
			}
	header ( 'Location: /index.php?v=1'); //take us back to the index with the verified parameter set
}
?>