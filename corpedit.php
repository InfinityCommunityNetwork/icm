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
if($_SESSION['login']===1)
{
	//Make sure the db is alive
	if ($mysqli->connect_errno) {
		die ("Failed to connect to MySQL server: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
	}

	//Select the db
	$mysqli->select_db($dbname);

	$user= $_SESSION['username'];

	//Get a db query  
	$result = $mysqli->query("SELECT * FROM `icmdb.Groups` WHERE Owner = \"" . $user . "\"");

	//Get the results
	$row = $result->fetch_array();

	//Pass the results into the forms
	echo
	("
		<html>
		<head>
			<link rel=\"stylesheet\" type=\"text/css\" href=\"style.css\" />
		</head>
		<body>
		<div class=\"menu\">
	");
	include "menu.php";
	echo
	("
		</div>
		<div class=\"main\">
		<form name=\"input\" action=\"corpeditaction.php\" method=\"post\">
		<table border='1'>
		<tr>
		<th>Ticker</th>
		<td><input type=\"text\" id=\"Textbox\" name=\"Ticker\"/ value=\"" . $row['Ticker'] . "\" maxlength=\"6\" /></td>
		</tr>
		<tr>
		<th>Logo URL</th>
		<td><input type=\"text\" id=\"Textbox\" name=\"LogoURL\" value=\"" . $row['LogoURL'] . "\" maxlength=\"255\"/></td>
		</tr>
		<tr>
		<th>Corp Name</th>
		<td><input type=\"text\" id=\"Textbox\" name=\"CorpName\" value=\"" . $row['GroupName'] . "\" maxlength=\"255\" /></td>
		</tr>
		<tr>
		<th>Description</th>
		<td><textarea name=\"Desc\" cols=\"40\" rows=\"7\">" . $row['Description'] . "</textarea></td>
		</tr>
		<tr>
		<th>Homepage</th>
		<td><input type=\"text\" id=\"Textbox\" name=\"Website\" value=\"" . $row['Website'] . "\" maxlength=\"255\" /></td>
		</tr>"
		);
		
		//check the checkboxes if they were checked originally.
		if($row['AllowMulti'] = "1") {
			echo(
			"<tr>
			<th>Allows Multiclanning?</th>
			<td><input type=\"checkbox\" name=\"allowMulti\" value=\"1\" checked=\"checked\" />(MUST be checked to continue to display as allowing Multiclanning)</td>
			</tr>"
			);
		
		} else {
			echo(
			"<tr>
			<th>Allows Multiclanning?</th>
			<td><input type=\"checkbox\" name=\"allowMulti\" value=\"1\" />(MUST be checked to continue to display as allowing Multiclanning)</td>
			</tr>"
			);
		}

		if($row['JoinMode'] = "4") {
			echo(
			"<tr>	
			<th>Recruiting?</th>
			<td><input type=\"checkbox\" name=\"isOpen\" value=\"4\" checked=\"checked\" /> (MUST be checked to continue to display as Recruiting)</td>
			</tr>"
			);
		} else {
			echo(
			"<tr>	
			<th>Recruiting?</th>
			<td><input type=\"checkbox\" name=\"isOpen\" value=\"4\" /> (MUST be checked to continue to display as Recruiting)</td>
			</tr>"
			);
		}
		echo(
		"</table>
		<input type=\"submit\" value=\"Edit\" />
		</form>
		<br />
		<a href=mycorp.php>Cancel and return to corp page</a><br />
		<a href=index.php>Return to Main Page</a>
		</div>
		</body>
		</html>"
	);

} else {
	//Go to index
	header( 'Location: '. $siteURL .'/index.php');
}

$mysqli->close($con);
?>
