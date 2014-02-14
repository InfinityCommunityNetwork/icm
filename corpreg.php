<?php
session_start();

//make sure you're actually logged in
if($_SESSION['login']===1)
{
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
		"</div>
		<div class=\"main\"><br /><h1>Corp Registration</h1><br />
		<body>
		<!-- <p><a href=\"index.php\"><- Back to Main Page</a></p><br /> -->

		<form name=\"input\" action=\"corpregaction.php\" method=\"post\">
		Corporation Ticker <br />
			<input type=\"text\" id=\"Textbox\" name=\"Ticker\" maxlength=\"6\" /> (Max 6 characters)<br />
		Corporation Name: <br />
			<input type=\"text\" name=\"GroupName\" maxlength=\"255\"/><br />
		Logo URL: <br />
			<input type=\"text\" name=\"LogoURL\" maxlength=\"255\" /><br />(We accept logo sizes up to 256 by 256 pixels. Logos are automatically resized.)<br />
		Homepage URL: <br />
			<input type=\"text\" name=\"Website\" maxlength=\"255\" /><br />
		<input type=\"checkbox\" name=\"JoinMode\" value=\"4\" /> Tick if you're accepting applicants!<br />
		<textarea name=\"Description\" cols=\"40\" rows=\"7\">Enter a Corp Description here...
		</textarea><br /><br />
		<input type=\"checkbox\" name=\"AllowMulti\" value=\"1\" /> Tick if you allow multi-clanning<br />
		<input type=\"submit\" value=\"Submit\" />
		</form> 
		</body>
		</html>
	");
}
else
{
	header( 'Location: /index.php');
}

?>