<?php 
/**
 * Installer for Infinity Corp Manager
 *
 * This file is to be run once for the initial installation of the application on a server.
 * This file performs the following functions:
 *
 * * Gets configuration settings from user via HTML form
 * * Creates database and tables if it does not exist
 * * Stores settings in config.php
 *
 * @package Infinity_Corp_Manager
 * @license Creative Commons Attribution-NonCommercial-ShareAlike (http://creativecommons.org/licenses/by-nc-sa/3.0/)
 * @author Harley Fagetter (http://github.com/Topperfalkon/)
 * @author Hamish Robertson (http://github.com/hrobertson/)
 */

header('Content-Type: text/html; charset=utf-8');

// Form to output, unless form has been submitted
if (!isset($_POST['submit'])) {
	echo "<html>
	<head>
		<title>ICM Installation</title>
	</head>
	<body>
		<p>Enter your database connection details here. The database will be created if it does not exist.<br />If you are using shared hosting you will have to create the database via your hosting control panel. Please use utf8.</p>
		<form action=\"install.php\" method=\"post\">
			<p>Database Server: <input type=\"text\" name=\"dbserver\" /></p>
			<p>Database Name: <input type=\"text\" name=\"dbname\" /></p>
			<p>DB Username: <input type=\"text\" name=\"dbuser\" /></p>
			<p>DB Password: <input type=\"password\" name=\"dbpass\" /></p>
			<p>Please provide the URL of this ICM installation. eg http://example.com/ or http://test.example.com/infinity/</p>
			<p>Site URL: <input type=\"text\" name=\"siteurl\" /></p>
			<p><input type=\"submit\" name=\"submit\" value=\"Submit\">
		</form> 
	</body>
</html>";
	
} else {

	// Copy form data into variables for better code readability
	$dbserver = $_POST['dbserver'];
	$dbname = $_POST['dbname'];
	$dbuser = $_POST['dbuser'];
	$dbpass = $_POST['dbpass'];
	
	$mysqli = new mysqli($dbserver, $dbuser, $dbpass);
	
	if ($mysqli->connect_errno) {
		die ("Failed to connect to MySQL server: ({$mysqli->connect_errno}) {$mysqli->connect_error}");
	}
	
	$mysqli->set_charset("utf8");

	// Connection established
	
	if (!$mysqli->select_db($dbname)) {
		echo "Database <em>$dbname</em> does not exist, attempting to create it... ";
		if ($mysqli->query("CREATE DATABASE $dbname DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci")) {
			echo "Database <em>$dbname</em> created successfully.<br/>";
		} else if ($mysqli->errno == 1044) { // 1044	SQLSTATE: 42000 (ER_DBACCESS_DENIED_ERROR) Access denied for user '%s'@'%s' to database '%s'
			die ("Failed to create database: User <em>$dbuser</em> does not have the required privilege<br/>");
		} else { // Other error
			die ("Failed to create database: ({$mysqli->errno}) {$mysqli->error}<br/>");
		}
	} else {
		$result = $mysqli->query("SELECT default_character_set_name FROM information_schema.SCHEMATA WHERE schema_name = '$dbname'");
		$row = $result->fetch_row();
		if ($row[0]!=='utf8') {
			die ("It looks like you have created the database <em>$dbname</em>, but you didn't set the character encoding to utf8. Did you know that every time you use something other than utf8, a kitten dies under a pile of quest�on m�rks�<br/>
	Please change the character encoding to utf8 and the collation to utf8_general_ci/s (You can use a different collation if you know which non-english language is going to be most commonly used.)");
		} else {
			echo "Database exists.<br />";
		}
	}
	
	// Database created. Let's create the tables
		
	if (($mysqli->multi_query("
		CREATE TABLE IF NOT EXISTS `icmdb.Users` (
			`UserID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`UserName` VARCHAR(45) NOT NULL,
			`Password` CHAR(60) NOT NULL,
			`Email` VARCHAR(80) NOT NULL,
			`EmailVisible` BIT(1) NULL,
			`EmailVerified` BIT(1) NULL,
			PRIMARY KEY (`UserID`),
			UNIQUE INDEX `UserName_UNIQUE` (`UserName` ASC) )
			ENGINE = MyISAM;

		CREATE TABLE IF NOT EXISTS `icmdb.Groups` (
		  `GroupID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
		  `GroupName` VARCHAR(80) NOT NULL,
		  `Ticker` VARCHAR(6) NOT NULL,
		  `Description` TEXT NULL,
		  `JoinMode` TINYINT NOT NULL DEFAULT 0 COMMENT '0 = Corp hidden | 1 = Closed | 2 = Invitation Only | 3 = Open to applications | 4 = Anyone can join',
		  `AllowMulti` TINYINT NOT NULL DEFAULT 1,
		  `HasLogo` BIT(1) NOT NULL DEFAULT 0,
		  `LogoURL` VARCHAR( 80 ) NULL,
		  `Website` VARCHAR(80) NULL,
		  `Owner` VARCHAR(45) NOT NULL,
		  PRIMARY KEY (`GroupID`),
		  UNIQUE INDEX `GroupName_UNIQUE` (`GroupName` ASC),
		  UNIQUE INDEX `Ticker_UNIQUE` (`Ticker` ASC) )
			ENGINE = MyISAM;

		CREATE TABLE IF NOT EXISTS `icmdb.Membership` (
		  `UserID` INT UNSIGNED NOT NULL ,
		  `GroupID` INT UNSIGNED NOT NULL ,
		  `Status` TINYINT UNSIGNED NOT NULL COMMENT '1 = Member | 2 = Applied | 3 = Invited | 4 = Leader' ,
		  PRIMARY KEY (`UserID`, `GroupID`) ,
		  INDEX `GroupID` (`GroupID` ASC) )
			ENGINE = MyISAM;"
	)) && ($mysqli->next_result()) && ($mysqli->next_result())) {
		echo 'Tables created.';
	} else {
		die ("Failed to create table(s): ({$mysqli->errno}) {$mysqli->error}<br />");
	}
	$mysqli->close();

	// Create config.php
	file_put_contents('config.php', array(
		'<?php',
		'// Database',
		"\$dbserver = '{$dbserver}';",
		"\$dbuser   = '{$dbuser}';",
		"\$dbname   = '{$dbname}';",
		"\$dbpass   = '{$dbpass}';",
		"\$mysqli = new mysqli(\$dbserver, \$dbuser, \$dbpass);",
		'',
		'// Other',
		"\$siteURL = '{$_POST['siteurl']}';",
		'?>')
	);
	
	echo ('Congratulations on succesfully installing the Infinity Corporation Manager Database. You may now like to delete install.php just to tidy up.<br />Settings have been saved to config.php.');
}
?>