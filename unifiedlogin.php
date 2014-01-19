<?php
//Load config
include 'config.php';

session_start();

//make sure the db is alive
if (!$mysqli) {
	die('Could not connect: ' . mysqli_error($mysqli
}
//declare variables from form
$user = mysqli_real_escape_string($mysqli, $_POST['user']);  //sanitising the input to protect db integrity
$pass=crypt($_POST["loginP"],'$1$test12345678$'); //yummy, encryption so that people don't leave plaintext passwords in the db (and for a bonus sanitises them too!)

//select the db
mysqli_select_db($mysqli, $dbname);

//check you don't already exist
$sql = "SELECT * FROM test_users WHERE UserName like '" . $user . "'";
$rResult = mysqli_query($mysqli, $sql);

if (mysqli_num_rows($rResult) > 0) {
	//call the sign-in module
	$sql = "SELECT Password FROM test_users WHERE UserName = '" . $user . "'";

	$query = mysqli_query($mysqli, $sql);
	$row = mysqli_fetch_array($query); //stupidly you still need to get the row from that query.
	$passcomp = $row['Password']; //but having fetched an array of the rows, you can specify the password from the array and pass it into a variable
	$comp=strcmp($pass,$passcomp); //compare the two strings
	
	if($comp !== 0) { //path to execute code if strings are not identical
		die ('Credentials invalid');
	} else {
		//check the user is verified.
		$sql = "SELECT Verified FROM test_users WHERE UserName = '" . $user . "'";

		$query = mysqli_query($mysqli, $sql);
		$row = mysqli_fetch_array($query); 
		$verified = $row['Verified']; 
		
		if($verified !="Y") { 
			//path to execute code if not verified
			echo
			("You have not verified your account.<br />
			If you've lost the email, click <a href=\"vemail.php?user=" . $user . "\">here</a> to have it resent.<br />
			");
		} else {
			//Create Session

			$_SESSION['login']=1;
			$_SESSION['username']= $user;

			//See if you have a corp already
			$sql2 = "SELECT * FROM Test_Corporations WHERE CreatorName like '" . $user . "'";
			$rResult2 = mysqli_query($mysqli, $sql2);
			if (mysqli_num_rows($rResult2) > 0) {
				$_SESSION['corpowner']=1;
			}
			
			header( 'Location: '. $siteURL .'/index.php'); //This returns us back to the index with session
		}
	}
}   
else
{
	header( 'Location: '. $siteURL .'/registerform.php?username=' . $user); //Now try to get user to register
} 
?>