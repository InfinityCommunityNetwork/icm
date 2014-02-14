<?php
/************************************************
*	Infinity Corp Manager Index Page
*	
*	Created by Harley Faggetter
*
*
*
*
*************************************************/
//Load config
include 'config.php';

session_start();

//Make sure you're actually logged in
if($_SESSION['login']===1) {
	//make sure the db is alive
	if ($mysqli->connect_errno) {
		die ("Failed to connect to MySQL server: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
	}

	//select the db
	$mysqli->select_db($dbname);

	$user= $_SESSION['username'];

	//Get a db query  
	$result = $mysqli->query("SELECT * FROM `icmdb.Groups` WHERE Owner = \"" . $user . "\"");

	//Get the results
	$row = $result->fetch_array();

	//Declare variables from inputs
	$username= $mysqli->real_escape_string ($_SESSION['username']);
	$groupname= $mysqli->real_escape_string ($_POST['GroupName']);
	$description= $mysqli->real_escape_string ($_POST['Description']);
	$ticker= $mysqli->real_escape_string ($_POST['Ticker']);
	$logoURL= $mysqli->real_escape_string ($_POST['LogoURL']);
	$website= $mysqli->real_escape_string ($_POST['Website']);
	$joinMode= $_POST['JoinMode'];
	$allowMulti= $_POST['AllowMulti'];

	//Pass in the page stylesheet and index menu
	echo 
	("
		<html>
		<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />
		</head>
		<div class=\"menu\">"
	);
	include 'menu.php';
	echo
	(
		"</div>"
	)

	//We need to build a query, but make sure it doesn't update unless all the required fields are the right length.

	//Check required fields are populated
	if(!strlen($groupname) > 0) {
		echo ("You missed some required data, please <a href=\"corpedit.php\">try again</a>.<br />
		Alternatively, return to <a href=\"mycorp.php\">your corp page</a> or the <a href=\"index.php\">main menu</a>."
		);
		die();
	}

	if(!strlen($ticker) > 0) {
		echo ("You missed some required data, please <a href=\"corpedit.php\">try again</a>.<br />
		Alternatively, return to <a href=\"mycorp.php\">your corp page</a> or the <a href=\"index.php\">main menu</a>."
		);
		die();
	}

	//Now that's out of the way, it should be easier to just do the updates individually

	if(strcmp($groupname,$row['GroupName']) != 0) {
		//check you don't already exist
		$sql = "SELECT * FROM `icmdb.Groups` WHERE GroupName like '" . $corpname . "'";
		$rResult = $mysqli->query($sql);
		if ($mysqli->num_rows($rResult) > 0) 
		{
			//The corp's already registered, so give the user a couple of options on how to proceed
			echo 
			(
			"<p>Your corp is already registered, please choose a different name instead.</p><br />
			<a href=\"corpedit.php\">Edit your Corp</a><br />
			<a href=\"index.php\">Back to Main Menu</a><br />"
			);
		
		} else {
			$mysqli->query("UPDATE `icmdb.Groups`
						set GroupName = '" . $groupname ."'
						where Owner = '" . $username . "'");

		}
	}
	if(strcmp($ticker,$row['CorpTicker']) != 0) {
		$sql2= "SELECT * FROM `icmdb.Groups` WHERE Ticker like '" . $corptick . "'";
		$rResult2 = $mysqli->query($sql2);

		if ($mysqli->num_rows($rResult2) > 0) {
			//The corp's already registered, so give the user a couple of options on how to proceed
			echo 
			(
			"<p>Your corp is already registered, please choose a different ticker instead.</p><br />
			<a href=\"corpedit.php\">Edit your Corp</a><br />
			<a href=\"index.php\">Back to Main Menu</a><br />"
			);
		
		} else {
			$mysqli->query("UPDATE `icmdb.Groups`
						set Ticker = '" . $ticker ."'
						where Owner = '" . $username . "'");

		}
	}
	
	if(strcmp($description,$row['Description']) != 0) {
		$mysqli->query("UPDATE `icmdb.Groups`
				set Description = '" . $description ."'
				where Owner = '" . $username . "'");

	}

	if(strcmp($website,$row['Website']) != 0) {
	$mysqli->query("UPDATE `icmdb.Groups`
				set Website = '" . $website ."'
				where Owner = '" . $username . "'");

	}

	if(strcmp($logoURL,$row['LogoURL']) != 0) {
		$mysqli->query("UPDATE `icmdb.Groups`
				set LogoURL = '" . $logoURL ."'
				where Owner = '" . $username . "'");

	}

	echo "Edit successful. Return to <a href=\"mycorp.php\">corp page</a> or <a href=\"index.php\">the main page</a>.";

} else {
	header( 'Location: '. $siteURL .'/index.php');
}
?>
