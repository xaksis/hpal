<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/user.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//all of DB interactions will take place from here


class Db{
	
	/*USER SPECIFIC FUNCTIONS*/
	
	public function insert_user($userObj)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM users where username='".$userObj->username."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$response->code = 1; 
			$response->explanation  = 'Username already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO users (username, 
										email, 
										firstname, 
										lastname, 
										password, 
										privilege,
										created) 
								VALUES('".$userObj->username."',
										'".$userObj->email."',
										'".$userObj->firstName."',
										'".$userObj->lastName."',
										'".$userObj->password."', 
										'".$userObj->privilege."',
										now())"; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = 0; 
			$response->explanation  = 'Success';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	public function insert_user_new($userArr)
	{
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM users where email='".$userArr['email']."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$response->code = 1; 
			$response->explanation  = 'Username already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO users ( username,
										  fullname,
										 email, 
										firstname, 
										lastname,  
										privilege,
										type,
										created) 
								VALUES(
										'".$userArr['displayName']."',
										'".$userArr['fullName']."',
										'".$userArr['email']."',
										'".$userArr['firstName']."',
										'".$userArr['lastName']."',
										2, 
										1,
										now())";  
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = mysql_insert_id(); 
			$response->explanation  = 'Success';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//function to delete a user
	public function delete_user($email)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "DELETE FROM users where email='".$email."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = 0; 
			$response->explanation = 'User Deleted!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while deleting User';
		}

		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//function to delete a user
	public function login_user($username, $password, &$user)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT *
				  FROM users 
				  WHERE email='$username' 
				  AND password=SHA1('$password')";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Login Successful!';
			while($row = mysql_fetch_assoc($result))
			{
				$user = $row;  
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'Invalid Username or Password!';
		}

		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//function to get user status
	public function get_user_status($email, &$user)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT *
				  FROM users 
				  WHERE email='$email'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = true; 
			$response->explanation = 'User Registered.';
			while($row = mysql_fetch_assoc($result))
			{
				$user = $row;  
			}
		}
		else
		{
			$response->code = false; 
			$response->explanation  = 'User unregistered!';
		}

		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//function to upgrade to federated
	public function upgrade_to_federated($email)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "update users set type=1 where email='$email'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = 0; 
			$response->explanation = 'User Deleted!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while deleting User';
		}
		mysql_close($connection);
		
		return $response; 
	}
	
/////////////////////////////////////////////////////////////////////////
	/*RESTAURANT SPECIFIC FUNCTIONS*/
/////////////////////////////////////////////////////////////////////////
	public function insert_restaurant($restaurant)
	{
		//create response object
		$response = new ResponseObject();
		
		//check if its a valid restaurant object
		if($restaurant->name == "NOT_SET" || $restaurant->address == "NOT_SET")
		{
			$response->code = 1; 
			$response->explanation  = 'Improper restaurant object';
		}
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM restaurants where name='".$restaurant->name."' and address = '".$restaurant->address."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
				
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$response->code = 1; 
			$response->explanation  = 'Restaurant already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO restaurants (name,
										address, 
										city,
										state,
										zipcode,
										country,
										phone,
										genre,
										area,
										adminRating,
										userRating,
										noOfRatings,
										lat,
										lon) 
								VALUES('".$restaurant->name."',
										'".$restaurant->address."',
										'".$restaurant->city."',
										'".$restaurant->state."',
										'".$restaurant->zipcode."', 
										'".$restaurant->country."',
										'".$restaurant->phone."',
										'".$restaurant->genre."',
										'".$restaurant->area."',
										".$restaurant->adminRating.",
										".$restaurant->userRating.",
										".$restaurant->noOfRatings.",
										'".$restaurant->lat."',
										'".$restaurant->lon."')"; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = 0; 
			$response->explanation  = 'Restaurant Added!';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response;	
	}
	
	//function to delete a restaurant
	public function delete_restaurant($restId)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "DELETE FROM restaurants where id='".$restId."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = 0; 
			$response->explanation = 'Restaurant Deleted!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while deleting Restaurant';
		}

		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//get restaurant by id
	public function get_restaurant_by_id($restId, &$restaurant)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select * FROM restaurants where id='".$restId."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = 0; 
			$response->explanation = 'Restaurant Found!';
			while($row = mysql_fetch_assoc($result))
			{
				$restaurant->name = $row['name']; 
				$restaurant->address = 	   $row['address'];
				$restaurant->phone =   	   $row['phone'];
				$restaurant->genre =   	   $row['genre'];
				$restaurant->city =    	   $row['city'];
				$restaurant->state =       $row['state'];
				$restaurant->zipcode =     $row['zipcode'];
				$restaurant->adminRating = $row['adminRating'];
			}
		}
		else
		{
			$response->code = 1; 
			$response->explanation  = 'Restaurant doesnt Exists';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to update a restaurant 
	public function update_restaurant($restId, $restaurant)
	{
		$query = $this->getUpdateRestaurantQuery($restaurant); 
		$query .= ' WHERE id='.$restId; 
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = 0; 
			$response->explanation = 'Update Successful!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while Updating Restaurant';
		}
		
		mysql_close($connection);
		
		return $response; 
	}

/////////////////////////////////////////////////////////////////////////
	/*IMAGE SPECIFIC FUNCTIONS*/
/////////////////////////////////////////////////////////////////////////

	public function insert_image($image)
	{
		//create response object
		$response = new ResponseObject();
		
		//check if its a valid restaurant object
		if($image->path_to_thumb == "NOT_SET")
		{
			$response->code = 1; 
			$response->explanation  = 'Improper image object';
		}
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		
		//insert new user into db
		$query = "INSERT INTO images (caption,
									comment,
									confirmed,
									imagePath, 
									user_id,
									dish_id,
									restaurant_id,
									created) 
							VALUES('".$image->caption."',
									'".$image->comment."',
									'".$image->confirmed."',
									'".$image->path_to_reg."',
									'".$image->user_id."', 
									'".$image->dish_id."',
									'".$image->restaurant_id."',
									now())"; 
		mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		$response->code = 0; 
		$response->explanation  = 'Image Saved!';

		mysql_close($connection);
		return $response;	
	}

	//get restaurant by id
	public function get_unconfirmed_images_for_user($userId, &$pictures)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		
		$query = "SELECT A.id, A.restaurant_id, A.imagePath, B.name FROM images AS A, restaurants AS B WHERE A.restaurant_id = B.id AND user_id =".$userId." AND A.confirmed = 0 AND A.deleted=0 ORDER BY created DESC";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = 0; 
			$response->explanation = 'Pictures Found!';
			$count = 0; 
			while($row = mysql_fetch_assoc($result))
			{
				$pictures[$count] = $row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 1; 
			$response->explanation  = 'Error';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to update image caption
	public function update_image_caption($id, $caption, $dishId)
	{
		$query = "UPDATE images SET caption='".$caption."', dish_id=".$dishId.", confirmed=1 WHERE id=".$id; 
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = $id; 
			$response->explanation = 'Update Successful!';
		}
		else
		{
			$response->code = 0;
			$response->explanation = 'Error while Updating Image';
		}
		
		mysql_close($connection);
		return $response; 
	}
	
	//function to delete image
	public function delete_image($id)
	{
		$query = "UPDATE images SET deleted=1 WHERE id=".$id; 
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = $id; 
			$response->explanation = 'delete Successful!';
		}
		else
		{
			$response->code = 0;
			$response->explanation = 'Error while Deleting Image';
		}
		
		mysql_close($connection);
		return $response; 
	}
	
//////////////////////////////////////////////////////////
///////////////////private functions//////////////////////
//////////////////////////////////////////////////////////
	
	private function getUpdateRestaurantQuery($restaurant)
	{
		$query = 'UPDATE restaurants SET ';
		$pre = false; 
		if($restaurant->name != 'NOT_SET')
		{
			$pre = true; 
			$query .= 'name = "'.$restaurant->name.'"'; 
		}
		if($restaurant->address != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true; 
			$query .= 'address = "'.$restaurant->address.'"'; 
		}
		if($restaurant->city != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'city = "'.$restaurant->city.'"'; 
		}
		if($restaurant->state != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'state = "'.$restaurant->state.'"'; 
		}
		if($restaurant->zipcode != 'NOT_SET') 
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'zipcode = "'.$restaurant->zipcode.'"'; 
		}
		if($restaurant->country != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'country = "'.$restaurant->country.'"'; 
		}
		if($restaurant->phone != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'country = "'.$restaurant->phone.'"'; 
		}
		if($restaurant->genre != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'genre = "'.$restaurant->genre.'"'; 
		}
		if($restaurant->adminRating != 0) 
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'adminRating = '.$restaurant->adminRating; 
		}
		if($restaurant->userRating != 0)
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'userRating = '.$restaurant->userRating; 
		}
		if($restaurant->noOfRatings != 0)
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'noOfRatings = '.$restaurant->noOfRatings; 
		}
		if($restaurant->lat != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'lat = "'.$restaurant->lat.'"'; 
		}
		if($restaurant->lon != 'NOT_SET')
		{
			if($pre)
				$query.=', ';
			$pre = true;
			$query .= 'lon = "'.$restaurant->lon.'"';
		}
		
		return $query; 
	}
	
	
}
?>