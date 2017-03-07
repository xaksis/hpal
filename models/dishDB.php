<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/image.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/user.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dish.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

//all of dish DB interactions will take place from here
class DishDb{
	
	/*USER SPECIFIC FUNCTIONS*/
	
	public function insert_dish($dish)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT id FROM dishes where name='".$dish->name."' and restaurant_id=".$dish->restaurant_id;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$response->code = $row['id']; 
			$response->explanation  = 'dish already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO dishes (name, 
										description, 
										user_id, 
										restaurant_id,
										noOfRatings,
										rating,
										created) 
								VALUES('".$dish->name."',
										'".$dish->description."',
										'".$dish->user_id."',
										'".$dish->restaurant_id."',
										1,
										'".$dish->rating."',
										now())";
			//echo $query; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = mysql_insert_id(); 
			$response->explanation  = 'Success';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	public function insert_scraped_dish($name, $desc, $price, $restId, $connection)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		//$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT id FROM dishes where name='$name' and restaurant_id=$restId";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$response->code = $row['id']; 
			$response->explanation  = 'dish already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO dishes (name, 
										description, 
										user_id, 
										restaurant_id,
										noOfRatings,
										rating,
										price,
										created) 
								VALUES( '$name',
									    '$desc',
									    0,
										'$restId',
										0,
										0,
										'$price',
										now())";
			echo $query, '<br/>'; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = mysql_insert_id(); 
			$response->explanation  = 'Dish added!';
		}
		mysql_free_result($result);
		//mysql_close($connection);
		
		return $response; 
	}
	
	//function to update dish feature
	public function update_dish_feature($dId, $fId, $connection)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		//$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM dishfeatures where dish_id=$dId and feature_id=$fId";
		echo $query,'<br/>';
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$response->code = 0;
			$response->explanation  = 'feature already exists for this dish';
		}
		else
		{
			//insert feature into db
			$query = "INSERT INTO dishfeatures (dish_id, feature_id) VALUES( $dId, $fId)";
			//echo $query; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = 1; 
			$response->explanation  = 'feature updated';
		}
		mysql_free_result($result);
		//mysql_close($connection);
		
		return $response; 
	}
	
	//function to get feature id given feature name
	public function get_feature_id_for_name($fName, $connection)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		//$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "select id from features 
				  where name='$fName'
				  AND fType=1";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$response->code = $row['id'];
			$response->explanation = 'Feature Found!';
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'Feature could not be found';
		}
		mysql_free_result($result);
		//mysql_close($connection);
		return $response; 
	}
	
	
	//function to update dish
	public function update_rating($id, $dish)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "update dishes set noOfRatings=noOfRatings+1, rating=rating+".$dish->rating." where id='".$id."'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = $id; 
			$response->explanation = 'Rating Updated!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while updating dish';
		}
		
		return $response; 
	}
	
	//function to delete a user
	public function delete_dish($id)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "alter table dishes set deleted=1 where id='".$id."'";
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
	
	//function to add dish review. if a review has been added by the user
	//don't do anything
	public function insert_dishReview($id, $dish, $comment)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT id FROM dishreviews where user_id='".$dish->user_id."' and restaurant_id=".$dish->restaurant_id." and dish_id=".$id;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$response->code = $row['id']; 
			$response->explanation  = 'dishReview already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO dishreviews (comment, 
										dish_id, 
										user_id, 
										restaurant_id,
										rating,
										created) 
								VALUES('".$comment."',
										'".$id."',
										'".$dish->user_id."',
										'".$dish->restaurant_id."',
										'".$dish->rating."',
										now())";
			//echo $query; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = mysql_insert_id(); 
			$response->explanation  = 'Success. Dish Review Added!';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//get dishes for a restaurant
	public function get_n_dishes_for_restaurants($restId, $start, $n, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT dishes.id as id, gdishes.id as gid, dishes.name, dishes.rating, dishes.description as dscr, noOfRatings, imagePath, gdishes.description, dishreviews.comment 
					FROM dishes LEFT OUTER JOIN gdishes ON dishes.gdish_id=gdishes.id, images, dishreviews
					where dishes.id = images.dish_id 
					AND images.deleted=0
					AND dishes.id = dishreviews.dish_id
					and dishes.restaurant_id=".$restId." 
					GROUP BY dishes.id ORDER BY dishes.rating/noOfRatings DESC 
					LIMIT ".$start.", ".$n;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
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
	
	//get dishes for a restaurant
	public function get_n_dishes_given_restaurant_name($restName, $start, $n, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT restaurants.id as rid, dishes.id as id, dishes.name, rating, dishes.noOfRatings, imagePath, description 
					FROM restaurants, dishes
					LEFT JOIN images on dishes.id = images.dish_id
					WHERE dishes.restaurant_id = restaurants.id
					AND restaurants.name ='$restName' 
					GROUP BY dishes.id ORDER BY rating/dishes.noOfRatings DESC 
					LIMIT ".$start.", ".$n;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
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
	
	//get dishes for a restaurant
	public function get_n_dishes_given_restaurant_name_and_dish_str($restName, $sstr, $start, $n, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT restaurants.id as rid, dishes.id as id, dishes.name, rating, dishes.noOfRatings, imagePath, description 
					FROM restaurants, dishes
					LEFT JOIN images on dishes.id = images.dish_id
					WHERE dishes.restaurant_id = restaurants.id
					AND restaurants.name ='$restName'
					AND dishes.name like '%$sstr%'
					GROUP BY dishes.id ORDER BY rating/dishes.noOfRatings DESC 
					LIMIT ".$start.", ".$n;
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
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
	
	public function get_top_n_dishes($n, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT d.id AS did, d.name AS dname, r.name AS rname, r.id AS rid, d.rating, d.noOfRatings, i.imagePath, d.description, dr.comment, d.gdish_id as gdid
					FROM dishes AS d, images AS i, restaurants AS r, dishreviews AS dr
					WHERE d.id = i.dish_id
					AND d.restaurant_id = r.id
					AND dr.dish_id = d.id
					AND i.deleted =0
					GROUP BY d.id
					ORDER BY d.rating / d.noOfRatings DESC
					LIMIT 0 , $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no dishes';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get description for a dish
	public function get_dish_description($did, &$desc)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "select * from dishreviews 
				  where dish_id=$did
				  AND comment != ''
				  ORDER BY created DESC
				  LIMIT 0,1";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Description Found!';
			$count=0;
			$desc = mysql_fetch_assoc($result);
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no description';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	
	//function to get search results for dishes
	public function get_dishes_for_search($s_str, $start, $n, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query	= "select d.id, d.restaurant_id, d.name, d.rating, d.noOfRatings, i.imagePath 
					from
					dishes as d left join images as i on d.id = i.dish_id
					where d.name like '%$s_str%'
					group by d.id
					order by d.rating/d.noOfRatings desc
					LIMIT $start , $n";
					
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no dishes';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	
	
	//function to get top n restaurants for a given dish
	public function get_top_n_restaurants_for_dish($n, $str, &$rests)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT DISTINCT d.id AS did, d.name AS dname, d.rating / d.noOfRatings AS drating, r. *
					FROM restaurants AS r, dishes AS d
					WHERE d.restaurant_id = r.id
					AND d.name LIKE '%$str%'
					UNION
					SELECT 0 , c.name AS dname, restaurants.adminrating AS drating, restaurants. *
					FROM restaurants, restcategories AS rc, categories AS c
					WHERE rc.restaurant_id = restaurants.id
					AND rc.category_id = c.id
					AND c.name LIKE '%$str%'
					ORDER BY drating desc
					LIMIT 0 , $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
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
			$response->explanation  = 'No places found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	
	//function to get top n restaurants for a given dish
	public function get_all_restaurants_for_dish($dishName, &$rests)
	{
		//echo $dishName; 
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT restaurants.*
				FROM dishes, restaurants
				WHERE dishes.name LIKE '%$dishName%'
				AND dishes.restaurant_id = restaurants.id
				GROUP BY restaurants.id
				ORDER BY dishes.rating / dishes.noOfRatings";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
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
			$response->explanation  = 'No places found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get n images for a given dish
	public function get_n_images_for_dish($n, $dishId, &$images)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT *
					FROM images
					WHERE dish_id=$dishId
					LIMIT 0 , $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Images found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$images[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No image found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get generic dishes from the database
	public function get_gDishes(&$gDishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM gdishes";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'gDishes found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$gDishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No dishes found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get generic dishes from the database
	public function get_new_gDishes($start, $n, &$gDishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM gdishes order by created desc limit $start, $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'gDishes found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$gDishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No dishes found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	
	//function to get generic dish detail by id
	public function get_gDish_by_id($id, &$row)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM gdishes where id=$id";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'gDishes found!';
			$row = mysql_fetch_assoc($result);
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No dishes found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}

	//function to update generic dish
	public function update_gDish($id, $desc, $family)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "update gdishes set description='$desc', familyName='$family' where id=$id";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = $id; 
			$response->explanation = 'gDish Updated!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while updating gdish';
		}
		
		return $response; 
	}
	
	//function to insert a new generic dish
	public function insert_gDish($name, $desc, $family)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT id FROM gdishes where name='$name'";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject(); 
		
		//check if results returned
		if(mysql_num_rows($result)>0)
		{
			$row = mysql_fetch_assoc($result);
			$response->code = $row['id']; 
			$response->explanation  = 'gdish already Exists';
		}
		else
		{
			//insert new user into db
			$query = "INSERT INTO gdishes (name, 
										description, 
										familyName,
										created) 
								VALUES('$name',
										'$desc',
										'$family',
										NOW())";
			//echo $query; 
			mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
			$response->code = mysql_insert_id(); 
			$response->explanation  = 'Success';
		}
		mysql_free_result($result);
		mysql_close($connection);
		
		return $response; 
	}
	
	//function to add Generic dish id to dish
	public function add_generic_to_dish($dId, $gdId)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "update dishes set gDish_id=$gdId where id=$dId";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		
		//create response object
		$response = new ResponseObject();
		
		if($result)
		{
			$response->code = $dId; 
			$response->explanation = 'dish Updated!';
		}
		else
		{
			$response->code = 1;
			$response->explanation = 'Error while updating gdish';
		}
		
		return $response; 
	}
	
	//function to get dish detail by id
	public function get_dish_by_id($id, &$row)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * FROM dishes where id=$id";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Dishes found!';
			$row = mysql_fetch_assoc($result);
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No dishes found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get dishes with a particular gDish_id
	public function get_dishes_by_gDish_id($start, $n, $gd_id, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT dishes.id as dId, dishes.name as dName, dishes.rating, dishes.noOfRatings, imagePath, caption, dishes.restaurant_id, gDish_id, images.user_id as user_id, restaurants.name as rName 
				  FROM dishes, images, restaurants 
				  WHERE dishes.id=dish_id 
				  AND dishes.restaurant_id=restaurants.id
				  AND gDish_id=$gd_id 
				  GROUP BY dishes.id 
				  ORDER BY dishes.rating/dishes.noOfRatings desc
				  LIMIT $start, $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'dishes found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No dishes found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get dishes given dish family
	public function get_n_dishes_given_dish_family($start, $n, $family, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query = "SELECT * from dishes, gdishes, images 
				  WHERE gdish_id=gdishes.id 
				  AND dish_id=dishes.id 
				  AND familyName='$family' 
				  GROUP BY gdishes.name
				  LIMIT $start, $n";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'gDishes found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'No dishes found';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response;
	}
	
	//function to get autocomplete names for dishes
	public function get_autocomplete_names($s_str, $start, $n, &$names)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query	= "SELECT name
					FROM categories
					WHERE name LIKE '%$s_str%'
					UNION
					SELECT name
					FROM gdishes
					WHERE name LIKE '%$s_str%'
					UNION
					SELECT familyName
					FROM gdishes
					WHERE familyName like '%$s_str%'
					UNION
					SELECT name
					FROM dishes
					WHERE name LIKE '%$s_str%'
					LIMIT $start , $n";
					
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Restaurants Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$names[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no dishes';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get autocomplete names for dishes
	public function get_dish_categories_for_restaurant($restId, &$dcats)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query	= "SELECT DISTINCT features.name, features.id
					FROM restaurants, dishes, features, dishfeatures
					WHERE restaurants.id = dishes.restaurant_id
					AND dishes.id = dishfeatures.dish_id
					AND dishfeatures.feature_id = features.id
					AND restaurants.id =$restId";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'Menu Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dcats[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no Menu';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	//function to get dishes in a certain category for a restaurant
	public function get_restaurant_dishes_for_given_feature($restId, $featureId, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query	= "SELECT dishes.*
					FROM restaurants, dishes, features, dishfeatures
					WHERE restaurants.id = dishes.restaurant_id
					AND dishes.id = dishfeatures.dish_id
					AND dishfeatures.feature_id = features.id
					AND restaurants.id =$restId
					AND features.id = $featureId";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'dishes Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no Dish';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
	public function get_uncategorized_restaurant_dishes($restId, &$dishes)
	{
		include($_SERVER['DOCUMENT_ROOT'].'/hp/DB.php');
		//open database connection
		$connection = mysql_connect($host, $user, $pass) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//insert data
		$query	= "SELECT dishes . *
					FROM restaurants, dishes
					LEFT OUTER JOIN dishfeatures ON dishes.id = dishfeatures.dish_id
					WHERE restaurants.id = dishes.restaurant_id
					AND restaurants.id =$restId
					AND dishfeatures.feature_id IS NULL";
		//execute query
		$result = mysql_query($query) or die ('Error in query: $query. ' . mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$response->code = mysql_num_rows($result); 
			$response->explanation = 'dishes Found!';
			$count=0;
			while($row = mysql_fetch_assoc($result))
			{
				$dishes[$count]=$row; 
				$count++;
			}
		}
		else
		{
			$response->code = 0; 
			$response->explanation  = 'no Dish';
		}
		
		mysql_free_result($result);
		mysql_close($connection);
		return $response; 
	}
	
}
	
?>