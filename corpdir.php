<?php
/************************************************
*	Infinity Corp Manager Index Page
*	
*	Created by Harley Faggetter
*
*
*
*************************************************/
//Load config
include 'config.php';

//Start the session
session_start();

//Make sure the db is alive
if ($mysqli->connect_errno) {
	die ("Failed to connect to MySQL server: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
}

//Select the db
$mysqli->select_db($dbname);

//Get a db query  
$result = $mysqli->query("SELECT * FROM `icmdb.Groups`");

//Put in a decent header
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
("
	</div>
	<div class=\"main\"><br /><h1>Corp Directory</h1><br />
");

//Print it in a HTML table
echo 
("
	<table border='1' class=\"directory\">
	<tr>
	<th>Ticker</th>
	<th>Corporation Name</th>
	<th>Creator</th>
	<th>Recruiting?</th>
	<th>Allow Multi-Clanning?</th>
	<th>Homepage URL</th>
	</tr>
");

//Pass the values into an array loop
while($row = $result->fetch_array())
{
	echo "<tr>";
	echo "<td>" . $row['Ticker'] . "</td>";
	echo "<td><a href=\"corpdetails.php?corpid=" . $row['GroupID'] ."\">" . $row['GroupName'] . "</a></td>";
	echo "<td>" . $row['Owner'] . "</td>";
	echo "<td>" . $row['JoinMode'] . "</td>"; //Put an if in here to handle different states
	echo "<td>" . $row['AllowMulti'] . "</td>";
	echo "<td><a href=\"". $row['Website'] ."\">" . $row['Website'] . "</a></td>";
	echo "</tr>";
}
echo "</table>";

//Let people go back the nice way if they haven't found what they're looking for
echo "<br /><br /><p class=\"dirhp\"><a href=\"index.php\">Return to Main Page</a></p></div></html>";

//Don't need the db connection any more, so close it.
$mysqli->close($con);
?>
