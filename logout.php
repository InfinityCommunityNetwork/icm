<?php
session_start();

//remove all the variables in the session 
$_SESSION = array();
// destroy the session 
session_destroy(); 

header( 'Location: /index.php');
?>