<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//all of DB interactions will take place from here


class RestaurantDb{
	
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
			$response->code = 0; 
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
										lon,
										created) 
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
										1,
										'".$restaurant->lat."',
										'".$restaurant->lon."',
										now())"; 
			mysql_query($query) or die ("Error in query: $query. " . mysql_error());
			$response->code = mysql_insert_id(); 
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
		$query = "Select restaurants.*, count(*) as imgcnt
					FROM restaurants left join images
					ON restaurants.id = images.restaurant_id
					where restaurants.id='$restId'
					GROUP BY restaurants.id";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if(mysql_num_rows($result)>0)
		{
			$response->code = $restId; 
			$response->explanation = 'Restaurant Found!';
			while($row = mysql_fetch_assoc($result))
			{
				$restaurant->id = 	   $row['id'];
				$restaurant->name = 	   $row['name']; 
				$restaurant->address = 	   $row['address'];
				$restaurant->phone =   	   $row['phone'];
				$restaurant->genre =   	   $row['genre'];
				$restaurant->city =    	   $row['city'];
				$restaurant->state =       $row['state'];
				$restaurant->zipcode =     $row['zipcode'];
				$restaurant->adminRating = $row['adminRating'];
				$restaurant->userRating  = $row['userRating'];
				$restaurant->noOfRatings = $row['noOfRatings']; 
				$restaurant->lat  = 	   $row['lat'];
				$restaurant->lon  = 	   $row['lon'];
				$restaurant->imgCount =    $row['imgcnt']; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'Restaurant doesnt Exists';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//get restaurant by id
	public function get_restaurant_by_name($restName, &$restaurant)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select restaurants.*
					FROM restaurants
					where name = '$restName'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if(mysql_num_rows($result)>0)
		{
			if($row = mysql_fetch_assoc($result))
			{
				$response->code = $row['id']; 
				$response->explanation = 'Restaurant Found!';
				$restaurant = $row; 
			}
		}
		else
		{
			$response->code = 0; 
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
	
	//get restaurant by id
	public function get_n_recent_restaurants($start, $n, &$restaurants)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT DISTINCT r.*
					FROM restaurants as r join images as i on r.id = i.restaurant_id
					WHERE i.created IS NULL 
					OR i.created = ( 
						SELECT MAX(i2.created)
						FROM images as i2
						where i2.restaurant_id = r.id
					)
					order by i.created desc 
					LIMIT ".$start.", ".$n;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$restaurants[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no restaurants';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//get n top rated restaurants for a given time.  
	public function get_top_n_restaurants($n, &$restaurants)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//get n restaurants
		$query = "Select restaurants.*, count(*) as imgcnt 
					FROM restaurants left join images
					ON restaurants.id = images.restaurant_id
					GROUP BY restaurants.id
					ORDER BY imgCnt desc, (adminRating / noOfRatings) desc 
					LIMIT 0, $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$restaurants[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no restaurants';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//get all subcategories in db
	public function get_subcategories(&$subcategories)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select distinct subcat FROM categories where subcat is not null and subcat != 'None' ORDER BY subcat";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$subcategories[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no subcategories';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//get cuisines for subcategory
	public function get_cuisines_for_subcat(&$cuisines, $subcat)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select id, name FROM categories where subcat='".$subcat."' and subcat != 'None' ORDER BY name";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$cuisines[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no subcategories';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//get category id given name
	public function get_category_id_by_name($catStr, &$row)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select * FROM categories where name like '%$catStr%'";
		echo $query, "<br/>"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result); 
			$response->code = $row['id']; 
			$response->explanation = 'Category found!';
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no categories';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to add category to restaurant
	public function add_category_to_restaurant($restId, $catId)
	{
		//create response object
		$response = new ResponseObject();
		
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM restcategories where restaurant_id='".$restId."' and category_id = '".$catId."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
				
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$response->code = 0; 
			$response->explanation  = 'Restaurant already has category';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO restcategories (restaurant_id, category_id) 
								VALUES(".$restId.",
										".$catId.")"; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = mysql_insert_id(); 
			$response->explanation  = 'Category added to Restaurant!';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response;	
	}
	
	//function to get a non dish image (if it exists) for a rest
	public function get_nondish_image_for_restaurant($restId, &$image)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select imagePath,caption FROM images where restaurant_id=$restId and dish_id=0 ORDER BY created limit 0,1";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Image found!';
			while($row = mysql_fetch_assoc($result))
			{
				$image=$row; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no images';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get a non dish image (if it exists) for a rest
	public function get_dish_image_for_restaurant($restId, &$image)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select imagePath,caption FROM images where restaurant_id=$restId ORDER BY created limit 0,1";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Image found!';
			while($row = mysql_fetch_assoc($result))
			{
				$image=$row; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no images';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get a non dish image (if it exists) for a rest
	public function get_categories_for_restaurant($restId, &$categories)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "Select categories.id, categories.name FROM categories, restcategories where category_id=categories.id and restaurant_id=$restId"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'categories found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$categories[$count]=$row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no categories';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	public function get_restaurant_names($str, $start, $n, &$rests)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//create query
		$query="SELECT name
				FROM restaurants
				WHERE name like '%$str%'
				LIMIT $start, $n"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'restaurants found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$rests[$count]=$row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No Restaurants';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	}
	
	public function get_restaurants_given_category($str, $start, $n, &$rests)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//create query
		$query="SELECT restaurants.*, count(*) as imgCount
				FROM restaurants left join images on restaurants.id = images.restaurant_id, restcategories, categories
				WHERE restaurants.id = restcategories.restaurant_id
				AND restcategories.category_id = categories.id
				AND categories.name LIKE '%$str%'
			    GROUP BY restaurants.id
				ORDER BY imgCount desc
				LIMIT $start, $n";
		//$query = "Select * from restaurants where name like '%$str%' order by name, adminRating limit $start, $n"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'restaurants found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$rests[$count]=$row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No Restaurants';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	}
	
	public function get_restaurants_given_search_str($str, $start, $n, &$rests)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//create query
		$query="SELECT rest . * , count( * ) AS imcnt
					FROM restaurants AS rest
					LEFT JOIN images AS i ON rest.id = i.restaurant_id
					WHERE rest.id
					IN
					(
						select r.id
						from restaurants as r left join restcategories as rc on r.id = rc.restaurant_id
						left join categories as c on rc.category_id = c.id
						left join dishes as d on r.id = d.restaurant_id
						left join gdishes as gd on d.gdish_id = gd.id
						left join images as i on r.id = i.restaurant_id
						where r.name like '%$str%'
						or c.name like '%$str%'
						or gd.familyName like '%$str%'
						or gd.name like '%$str%'
						or d.name like '%$str%'
					)
					group by rest.id
					order by imcnt desc,
					rest.adminRating/rest.noOfRatings desc
				LIMIT $start, $n";
		//$query = "Select * from restaurants where name like '%$str%' order by name, adminRating limit $start, $n"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'restaurants found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$rests[$count]=$row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No Restaurants';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	}
	
	//function to add restaurant review
	public function add_restaurant_review($restId, $userId, $title, $comment, $rating)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "INSERT INTO reviews (user_id, restaurant_id, created, rating, title, comment) 
				  VALUES($userId, $restId, now(), $rating, '$title', '$comment')";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = 0; 
			$response->explanation = 'Review Added!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while adding Review';
		}

		mysql_close($connection);
		
		return $response; 
	}
	
	//function to update restaurant rating
	public function update_restaurant_rating($restId, $rating, $oldRating)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		$query = ""; 
		if($oldRating != 0)
		{
			$rating = $rating-$oldRating;
			$query = "UPDATE restaurants SET adminRating=adminRating+$rating WHERE id=$restId";
		}
		else
		{
			$query = "UPDATE restaurants SET adminRating=adminRating+$rating, noOfRatings=noOfRatings+1 WHERE id=$restId";
		}
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = 0; 
			$response->explanation = 'Review Added!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while adding Review';
		}

		mysql_close($connection);
		
		return $response; 
	}
	
	public function get_restaurant_review_for_user($restId, $userId, &$review)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//create query
		$query="SELECT * from reviews 
				WHERE user_id=$userId
				AND restaurant_id=$restId
				ORDER BY created desc"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = 1; 
			$response->explanation = 'review found!';
			$row = mysql_fetch_assoc($result);
			$review=$row; 
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No Reviews';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	}
	
	//function to get users's review for a particular restaurant
	public function get_restaurant_reviews($restId, $start, $n, &$reviews)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//create query
		$query="SELECT reviews.id, reviews.created, reviews.restaurant_id, reviews.title, reviews.rating, reviews.comment,
				users.id, users.username, users.email, users.firstname, users.lastname, users.privilege
				FROM reviews, users
				WHERE reviews.user_id = users.id
				AND restaurant_id=$restId
				AND reviews.title != 'NONE'
				ORDER BY reviews.created desc
				LIMIT $start, $n"; 
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		//create response object
		$response = new ResponseObject();
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'restaurants found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$reviews[$count]=$row; 
				$count++; 
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No Reviews';
		}
		
		mysql_free_result($result);
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
			$query .= 'phone = "'.$restaurant->phone.'"'; 
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