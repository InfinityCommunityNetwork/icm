<?php
//Load config
include 'config.php';

session_start();

//make sure you're actually logged out
if($_SESSION['login']===1) {
	header( 'Location: /index.php');

} else {
	//Make sure the db is alive
	if (!$mysqli) {
		die('Could not connect: ' . mysqli_error($mysqli));
	}
		
	//Select the db
	mysqli_select_db($mysqli, $dbname);

	//Variables
	$username= trim(mysqli_real_escape_string($mysqli, $_POST['user']));
	$pass=crypt($_POST["passone"],'$1$test12345678$'); // MD5 is rubbish. Use BCrypt.
	$email= trim(mysqli_real_escape_string($mysqli, $_POST['email']));
	$canEmail=$_POST['canEmail'];

	//Checks for required fields
	if(!$username || !$_POST['passone'] || !$_POST['passtwo']) {
		die ("You missed some required data, please <a href=\"registerform.php\">try again</a>.");
	}

	if ($_POST['passone'] !== $_POST['passtwo']) {
		die ("The two passwords you entered did not match, please <a href=\"registerform.php\">try again</a>.");
	}
	
	//Check the new user isn't on the database either
	$rResult = mysqli_query($mysqli, "SELECT * FROM test_users WHERE UserName like '{$username}'");
	
	if (mysqli_num_rows($rResult) > 0) {
	//Provide some options in case user already exists. Either try to login again, or try to register again
		die ("This user already exists. Please <a href=\"index.php\">log in</a> or <a href=\"registerform.php\">try again.</a>");
	} else {
		//All good, now to add the new user to the database
		$sqladd="INSERT INTO test_users (UserName, Password, Email, CanEmail)
		VALUES('$username','$pass','$email','$canEmail')";

		if (!mysqli_query($mysqli, $sqladd)) {
			die('Error: ' . mysqli_error($mysqli));
		}
		
		//Push them to the verification page
		header("Location: ". $siteURL ."/vemail.php?user=" . $username);
	}
}
?>