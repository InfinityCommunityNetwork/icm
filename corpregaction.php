<?php
//Load config
include 'config.php';
session_start();

//make sure you're actually logged in
if($_SESSION['login']===1)
{
	//Make sure the db is alive
	if ($mysqli->connect_errno) {
		die ("Failed to connect to MySQL server: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
	}

	//Select the db
	$mysqli->select_db($dbname);

	//Declare inputs as variables
	$username= $mysqli->real_escape_string ($_SESSION['username']);
	$groupname= $mysqli->real_escape_string ($_POST['GroupName']);
	$description= $mysqli->real_escape_string ($_POST['Description']);
	$ticker= $mysqli->real_escape_string ($_POST['Ticker']);
	$logoURL= $mysqli->real_escape_string ($_POST['LogoURL']);
	$website= $mysqli->real_escape_string ($_POST['Website']);
	$joinMode= $_POST['JoinMode'];
	$allowMulti= $_POST['AllowMulti'];
	  
	//Check required fields are populated
	if(!strlen($groupname) > 0)
	{
		echo "You missed some required data, please <a href=\"corpreg.php\">try again</a>.";
		die();
	}

	if(!strlen($ticker) > 0)
	{
		echo "You missed some required data, please <a href=\"corpreg.php\">try again</a>.";
		die();
	}

	//check you don't already exist
	$sql = "SELECT * FROM `icmdb.Groups` WHERE GroupName like '" . $corpname . "'";
	$rResult = $mysqli->query($sql);
	$sqltwo = "SELECT * FROM `icmdb.Groups` WHERE Ticker like '" . $corptick . "'";
	$rResulttwo = $mysqli->query($sqltwo);
	//$sqlthree = "SELECT * FROM Corp_Membership WHERE Username ='" . $username . "' and Allow_Multi = Null"
	if ($mysqli->num_rows($rResult) > 0) 
	{
		//The corp's already registered, so give the user a couple of options on how to proceed
		echo 
		(
		"<p>Your corp is already registered, please choose a different name instead.</p><br />
		<a href=\"corpreg.php\">Back to Corp Registration</a><br />
		<a href=\"index.php\">Back to Main Menu</a><br />"
		);
	}
	//check you don't already exist

	elseif ($mysqli->num_rows($rResulttwo) > 0) 
	{
		echo 
		(
		"<p>Your corp ticker is already in use. Sorry about that.<br />
		Try another ticker name</p><br />
		<a href=\"corpreg.php\">Back to Corp Registration</a><br />
		<a href=\"index.php\">Back to Main Menu</a><br />"
		);
	}
	
	//We have to put the users into the database now
	else
	{
		$sqladd="INSERT INTO `icmdb.Groups` (GroupName, Owner, JoinMode, Ticker, Description, AllowMulti, Website, LogoURL)
		VALUES('$groupname','$username','$joinMode','$ticker','$description','$allowMulti','$website','$logoURL')";

		if (!($mysql->query($sqladd)))
		{
			die('Error: ' . $mysqli->error());
		}

		$_SESSION['corpowner']= 1;

		echo 
		(
			"Your corp has now been created.
			<br />
			<p><a href=\"index.php\">Return to main menu</a></p>"
		);
	} 
}
else
{
	header( 'Location: '. $siteURL .'/index.php');
}
?>