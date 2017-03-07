<?php  
session_start();
session_destroy(); 
echo 'Log out successful!';
$url = 'http://'.$_SERVER['HTTP_HOST'].'/hp/home.php';
header( 'Location: '.$url ) ;
?>