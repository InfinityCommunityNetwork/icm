<?php
session_start();

//make sure you're actually logged out
if($_SESSION['login']===1)
{
	header( 'Location: http://test.phoeniximperium.org/index.php');
}

else
{
//Create register form and populate the username field!
	echo
	(
		"<!DOCTYPE html>
		<html>
		<head>
		<title>Infinity Corp Manager Prototype</title>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />
		</head>
		<body>
		<p>The username you entered didn't seem to exist.<br />
		If you feel this was in error, please click <a href=\"index.php\">here</a> to try again. 
		<br />Otherwise, please register below
		<form name=\"input\" action=\"register.php\" method=\"post\">
		Username: <br />
		<input type=\"text\" id=\"Textbox\" name=\"user\" value=\"" . $_GET['username'] . "\" maxlength=\"255\" /> (required)<br />
		Password: <br />
		<input type=\"password\" id=\"Textbox\" name=\"passone\" maxlength=\"255\" /> (required)<br />
		Repeat Password: <br />
		<input type=\"password\" id=\"Textbox\" name=\"passtwo\" maxlength=\"255\" /> (required)<br />
		Email: <br />
		<input type=\"text\" id=\"Textbox\" name=\"email\" maxlength=\"254\" /> (required)<br /><br />
		Can other users email you?<input type=\"checkbox\" name=\"canEmail\" value=\"1\" /> (Admins will always be able to email you)<br />
		<!--<input type=\"text\" id=\"Textbox\" name=\"pass1\" maxlength=\"6\" />
		<input type=\"text\" id=\"Textbox\" name=\"pass1\" maxlength=\"6\" />
		<input type=\"text\" id=\"Textbox\" name=\"pass1\" maxlength=\"6\" /> -->
		<br /><input type=\"submit\" value=\"Register/Login\" />
		</form>
		</body>
		</html>"
	);
}
?>