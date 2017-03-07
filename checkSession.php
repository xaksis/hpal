<?php 
session_start(); 
if(isset($_SESSION['id']))
{
	echo 'Logged In'; 
}
else
{
	echo 'Not Logged In'; 
}
?>