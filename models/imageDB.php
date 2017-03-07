<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/user.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//all of DB interactions will take place from here


class ImageDb{

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
		
		$query = "SELECT A.id, A.restaurant_id, A.imagePath, B.name 
		FROM images AS A left join restaurants AS B on A.restaurant_id = B.id 
		WHERE user_id =".$userId." 
		AND A.confirmed = 0 AND A.deleted=0 
		ORDER BY A.created DESC";
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
	public function update_image_caption($id, $restId, $caption, $dishId)
	{
		$query = "UPDATE images SET caption='".$caption."', confirmed=1, dish_id=$dishId, restaurant_id=$restId WHERE id=".$id; 
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
	
	public function get_n_recent_images_by_rest_id($restId, $start, $n, &$images)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		
		$query = "SELECT imagePath, caption, dish_id FROM images WHERE restaurant_id =".$restId." AND confirmed=1 ORDER BY created DESC LIMIT ".$start.", ".$n;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = 0; 
			$response->explanation = 'Pictures Found!';
			$count = 0; 
			while($row = mysql_fetch_assoc($result))
			{
				$images[$count] = $row; 
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
	
	public function get_image_count_for_rest($restId)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		
		$query = "SELECT count(*) as imgCount FROM images WHERE restaurant_id =".$restId." AND confirmed=1 AND deleted=0";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result); 
			$response->code = $row['imgCount']; 
			$response->explanation = 'Pictures Found!';
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'Error';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	}
	
	//function to get images for a particular dish id
	public function get_images_for_dish($dishId, &$images)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		
		$query = "SELECT imagePath, caption FROM images WHERE dish_id =".$dishId." AND confirmed=1 ORDER BY created DESC";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Pictures Found!';
			$count = 0; 
			while($row = mysql_fetch_assoc($result))
			{
				$images[$count] = $row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'Error';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	
	}
	
//////////////////////////////////////////////////////////
///////////////////private functions//////////////////////
//////////////////////////////////////////////////////////
	
	
	
}
?>