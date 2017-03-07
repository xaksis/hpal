<?php 
	if(isset($_POST))
	{
		$username = $_POST['user'];
		$password = $_POST['pass'];
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT id, password, privilege, username FROM users where username='".$username."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			if(sha1($password) == $row['password'])
			{
				session_start();
				$_SESSION['user'] = $row['id'];
				$_SESSION['priv'] = $row['privilege']; 
				echo 'Success';
			}
			else
				echo 'Password Entered is not Valid';
		}
		else
		{
			echo "Username Doesn't Exist.";
		}
		
		mysql_free_result($result);
		mysql_close($connection);
	}
?>