<?php
define("MAPS_HOST", "maps.google.com");
define("KEY", "ABQIAAAAVfcO4S7FbILnfiImwwzXBRSBNnVE0C8U5RNREDUpFF6W9RBZVRTDAkRf4OC_lR7pXCX2sL_ddZ0pXg");
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restModel.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishModel.php';

//interface for different sites
abstract class ScrapeClass
{

	function __construct($targetURL, $userAgent, $restID, $siteID, $connection){
		$this->target_url = $targetURL;
		$this->userAgent = $userAgent;
		$this->connection = $connection;
		$this->restID = $restID;
		$this->siteID = $siteID;
		$this->totalReviews = 0;
		//default last review and user
		$this->lastReviewDate = strtotime("01-01-1990"); 
		$this->lastReview = ""; 
		//$this->checkLastReview();  	
	}
	
	public function setopt(&$ch)
	{
		curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
		curl_setopt($ch, CURLOPT_URL,$this->target_url);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10000);
		return; 
	}
	
	public function addDish($name, $desc, $price, $features)
	{
		$response = new ResponseObject(); 
		$name = $response->sanitize($name); 
		$desc = $response->sanitize($desc);
		$price = (float)substr($response->sanitize($price), 1);
		//create a DishModel object 
		$dm = new DishModel();
		echo $name," ",$desc," ",$price," ",var_dump($features)," ",$this->restID, '<br/>'; 
		$response = $dm->addScrapedDish($name, $desc, $price, $features, $this->restID, $this->connection);
		echo $response->explanation, "<br/>"; 
	}
	
	public function geocodeAddress($name, $adr, $city, $state, $zip, $rating, $phone, $categories)
	{
		$base_url = "http://" . MAPS_HOST . "/maps/geo?output=xml" . "&key=" . KEY;
		$fullAddress = $adr.",".$city.",".$state." ".$zip; 
		$geocode_pending = true;
		while ($geocode_pending) {
			$address = $fullAddress; 
			$request_url = $base_url . "&q=" . urlencode($address);
			$xml = simplexml_load_file($request_url) or die("url not loading");

			$status = $xml->Response->Status->code;
			if (strcmp($status, "200") == 0) {
			  // Successful geocode
			  $geocode_pending = false;
			  $coordinates = $xml->Response->Placemark->Point->coordinates;
			  $coordinatesSplit = explode(",", $coordinates);
			  // Format: Longitude, Latitude, Altitude
			  $lat = $coordinatesSplit[1];
			  $lng = $coordinatesSplit[0];
			  echo "lat: ", $lat, " long: ", $lng; 
			  
			  $this->insertRestaurant($name, $adr, $city, $state, $zip, $rating, $phone, $lat, $lng, $categories); 
			} else if (strcmp($status, "620") == 0) {
			  // sent geocodes too fast
			  //$delay += 100000;
			} else {
			  // failure to geocode
			  $geocode_pending = false;
			  echo "Address " . $address . " failed to geocoded. ";
			  echo "Received status " . $status . "\n";
			}
		}
	}
	
	public function insertRestaurant($name, $adr, $city, $state, $zip, $rating, $phone, $lat, $lng, $categories)
	{
		$response = new ResponseObject();
		//create a restaurant object
		$restaurant = new Restaurant(); 
		
		$restaurant->name =    	   $response->sanitize($name);
		$restaurant->address = 	   $response->sanitize($adr);
		$restaurant->phone =   	   $response->sanitize($phone);
		//$restaurant->genre =   	   $_POST['genre'];
		$restaurant->city =    	   $city;
		$restaurant->state =       $state;
		$restaurant->zipcode =     $zip;
		$restaurant->adminRating = $rating;
		$restaurant->lat 		 = $lat;
		$restaurant->lon		 = $lng;
		//create a restModel object 
		$restModel = new RestModel(); 
		var_dump($restaurant);
		var_dump($categories); 
		$response = $restModel->createRest($restaurant); 
		echo $response->explanation, "<br/>"; 
		//once we get response we need to get the id for the categories
		foreach($categories as $cat)
		{
			//if category exists get its id
			$cResp = $restModel->getCategoryIdByName($cat, $row);
			echo $cResp->explanation,"<br/>";
			if($cResp->code != 0)
			{
				$caResp = $restModel->addCategoryToRestaurant($response->code, $cResp->code);
				echo $caResp->explanation,"<br/>"; 
			}
		}
		
	}
	
	/*
	public function checkLastReview()
	{
		include('DB.php');
		$connection = mysql_connect($host, $user, $password) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//check if user already exists
		$query_lastReview = "SELECT created, comment from reviews r, reviewers u where u.id = r.reviewer_id and r.site_id=$this->siteID and r.restaurant_id=$this->restID order by created desc limit 1";
		$result = mysql_query($query_lastReview) or die(mysql_error());
		if(mysql_num_rows($result)>0)
		{
			$row=mysql_fetch_assoc($result);
			$this->lastReviewDate = strtotime($row['created']); 
			$this->lastReview = $row['comment'];  
		}
		mysql_free_result($result);
		mysql_close($connection);
	}
	
	public function insertIntoDB($username, $rating, $title, $dateOfReview, $comment)
	{
		include('DB.php');
		$userID = 0; 
		$connection = mysql_connect($host, $user, $password) or die('could not connect');
		//select database
		mysql_select_db($dbName) or die('could not select database');
		//check if user already exists
		$query_check = "SELECT id, contribution from reviewers where username='$username'";
		$result = mysql_query($query_check) or die(mysql_error()); 
		if(mysql_num_rows($result)>0)
		{ 
			$row=mysql_fetch_assoc($result); 
			$userID = $row['id'];
			//if user exists increase contribution count
			$contribution = $row['contribution']+1; 
			$update_contribution_query = "UPDATE reviewers SET contribution = $contribution WHERE id=$userID";
			mysql_query($update_contribution_query) or die('could not update contribution: '.mysql_error());
		}
		else
		{
			$insert_user_query="INSERT INTO reviewers(username, site_id, contribution) VALUES('$username', '$this->siteID', '1')";  
			mysql_query($insert_user_query) or die('could not insert user into db.');
			$userID = mysql_insert_id(); 
		}
		//insert review
		$insert_review_query="INSERT INTO reviews(reviewer_id, restaurant_id, site_id, rating, created, title, comment) VALUES('$userID', '$this->restID', '$this->siteID', '$rating', '$dateOfReview', '".$title."', '".$comment."')";
		//execute query
		mysql_query($insert_review_query) or die(mysql_error());
		//increase review count and rating sum for restaurant. 
		$query_update_rest = "UPDATE restaurants SET avgRating = avgRating+".$rating.", noOfRatings = noOfRatings+1 WHERE id=".$this->restID;
		mysql_query($query_update_rest) or die('could not update restaurant rating.');
		
		$this->totalReviews++;  
		mysql_free_result($result);
		mysql_close($connection);
	}
	*/
	public function setURL($targetURL)
	{
		$this->target_url = $targetURL; 
	}
	
	abstract public function parseHTML($html);
	
	public $target_url; 
	public $userAgent;
	public $restID; 
	public $siteID;
	public $totalReviews;
	public $connection;
	public $lastReviewDate;
	public $lastReview;
}

?>