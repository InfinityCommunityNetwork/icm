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
session_start();


//Check that you're logged in.
if($_SESSION['login']===1) {
	//Pass doctype, html headers and greeting
	echo
	(
		"<!DOCTYPE html>
		<html>
		<head>
			<title>Infinity Corp Manager Prototype</title>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"./style.css\" />
		</head>
		<body>
			<p>Welcome " . $_SESSION['username'] . "<br />"
	);
	//Check whether you've been logged in as a corp owner
	if($_SESSION['corpowner']===1) {
		echo "	<p><a href=\"./mycorp.php\" target=\"_top\">My Corp</a><br />";
	} else { //Otherwise show Corp Registration option	
		echo "	<p><a href=\"./corpreg.php\" target=\"_top\">Corp Registration</a><br />";
	}
	//Then show everything else
	echo
	(
	"	<p><a href=\"./corpdir.php\" target=\"_top\">Corp Directory</a><br />
		<p><a href=\"./logout.php\" target=\"_top\">Logout</a></p>
	</body>
	</html>"	
	);
//Show this if not logged in
} else {
	//But include this if you've just verified your email
	if($_GET['v'] === "1") {
		echo "You're now verified, please log in. <br />";
	}

	echo
	(
	"<!doctype html>
	<html>
	<head>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"./style.css\" />
		<title>Infinity Corp Manager Prototype</title>
	</head>
	<body>
		<h1 style=\"text-align:center;\">INFINITY Corp Manager</h1><br />
		<center>Version 0.1<br />
		<p>Please sign in to access this service.
		<form name=\"input\" action=\"./unifiedlogin.php\" method=\"post\">
			Username: <input type=\"text\" name=\"user\" /><br />
			Password: <input type=\"password\" name=\"loginP\" /><br />
			<input type=\"submit\" value=\"Register/Login\" /></center>
		</form>
	</body>
	</html>"
	);
}
?>
