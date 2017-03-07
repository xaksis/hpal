<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurantDB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/restaurant.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php'; 

class RestModel{

	//function to create a new restaurant
	public function createRest($restaurant)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->insert_restaurant($restaurant);
		
		return $response; 
	}
	
	//function to update restaurant
	public function updateRest($restId, $restaurant)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->update_restaurant($restId, $restaurant);
		
		return $response; 
	}
	
	//function to get restaurant by id
	public function getRestaurantById($id, &$restaurant)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_restaurant_by_id($id, $restaurant);
		
		return $response;
	}
	
	//function to get restaurant by name
	public function getRestaurantByName($name, &$restaurant)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_restaurant_by_name($name, $restaurant);
		
		return $response;
	}
	
	//function to get N latest restaurants
	public function getNewRestaurants($start, $n, &$restaurants)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_n_recent_restaurants($start, $n, $restaurants);
		
		return $response;
	}
	
	//function to get top N restaurants of all times
	public function getTopRestaurants($n, &$restaurants)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_top_n_restaurants($n, $restaurants);
		
		return $response;
	}
	
	//function to get a non dish image for a restaurant. 
	public function getNonDishImageForRestaurant($restId, &$image)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_nondish_image_for_restaurant($restId, $image);
		
		if($response->code == 0)
			$response = $db->get_dish_image_for_restaurant($restId, $image); 
		
		return $response;
	}
	
	//function to get subcategories for restaurants
	public function getSubCategories(&$subcategories)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_subcategories($subcategories);
		
		return $response;
	}
	
	//function to get subcategories for restaurants
	public function getCuisinesForSubcat(&$cuisines, $subcat)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_cuisines_for_subcat($cuisines, $subcat);
		
		return $response;
	}
	
	//function to add category to restaurant
	public function addCategoryToRestaurant($restId, $catId)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->add_category_to_restaurant($restId, $catId);
		
		return $response; 
	}
	
	//function to get categories for a restaurant
	public function getCategoriesForRestaurant($restId, &$categories)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_categories_for_restaurant($restId, $categories);
		
		return $response;
	}
	
	public function getCategoryIdByName($catStr, &$row)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_category_id_by_name($catStr, $row);
		
		return $response;
	}
	
	public function getRestaurantsGivenSearchStr($str, $start, $n, &$rests)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_restaurants_given_search_str($str, $start, $n, $rests);
		
		return $response;
	}

	public function getRestaurantsGivenCategory($str, $start, $n, &$rests)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_restaurants_given_category($str, $start, $n, $rests);
		
		return $response;
	}
	
	//function to get search result for a given string
	public function getRestaurantNames($str, $start, $n, &$restaurants)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_restaurant_names($str, $start, $n, $restaurants);
		
		return $response;
	}
	
	//function to add restaurant review
	public function addRestaurantReview($restId, $userId, $title, $comment, $rating)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		//check if this user has a rating already
		//if so, get that rating, update it, 
		//subtract from rest rating, and add new rating.
		$response = $db->get_restaurant_review_for_user($restId, $userId, $review);
		$oldRating = 0; 
		if($response->code == 1)
			$oldRating = $review['rating']; 
		$response = $db->add_restaurant_review($restId, $userId, $title, $comment, $rating);
		
		if($response->code == 0)
		{
			//if review was added, we need to update restaurant rating
			$response = $db->update_restaurant_rating($restId, $rating, $oldRating); 
		}
		
		return $response;
	}
	
	//function to add restaurant review
	public function getRestaurantReviews($restId, $start, $n, &$reviews)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_restaurant_reviews($restId, $start, $n, $reviews);
		
		return $response;
	}
	
	//function to get all categories by cat type
	public function getAllCategoriesByType($catType, $start, $n, &$cats)
	{
		$db = new RestaurantDb(); //create a DB class
		$response = new ResponseObject(); //and a response object  
		
		$response = $db->get_all_categories_by_type($catType, $start, $n, $cats);
		
		return $response;
	}
	
	//function to convert rating from number to stars
	public function ratingInStar($rating)
	{
		$starStr = 'http://'.$_SERVER['HTTP_HOST'].'/hp/images/star/star';
		if($rating>0 && $rating<=0.5)
			$starStr .= '0_5.png';
		else if($rating>0.5 && $rating<=1)
			$starStr .= '1.png';
		else if($rating>1 && $rating<=1.5)
			$starStr .= '1_5.png';
		else if($rating>1.5 && $rating<=2)
			$starStr .= '2.png';
		else if($rating>2 && $rating<=2.5)
			$starStr .= '2_5.png';
		else if($rating>2.5 && $rating<=3)
			$starStr .= '3.png';
		else if($rating>3 && $rating<=3.5)
			$starStr .= '3_5.png';
		else if($rating>3.5 && $rating<=4)
			$starStr .= '4.png';
		else if($rating>4 && $rating<=4.5)
			$starStr .= '4_5.png';
		else if($rating>4.5 && $rating<=5)
			$starStr .= '5.png';
		else
			$starStr .= '0_5.png';
			
		return $starStr; 
	}
	
	//function to convert rating from number to stars
	public function oRatingInStar($rating)
	{
		$starStr = 'http://'.$_SERVER['HTTP_HOST'].'/hp/images/star/oStar';
		if($rating>0 && $rating<=0.5)
			$starStr .= '0_5.png';
		else if($rating>0.5 && $rating<=1)
			$starStr .= '1.png';
		else if($rating>1 && $rating<=1.5)
			$starStr .= '1_5.png';
		else if($rating>1.5 && $rating<=2)
			$starStr .= '2.png';
		else if($rating>2 && $rating<=2.5)
			$starStr .= '2_5.png';
		else if($rating>2.5 && $rating<=3)
			$starStr .= '3.png';
		else if($rating>3 && $rating<=3.5)
			$starStr .= '3_5.png';
		else if($rating>3.5 && $rating<=4)
			$starStr .= '4.png';
		else if($rating>4 && $rating<=4.5)
			$starStr .= '4_5.png';
		else if($rating>4.5 && $rating<=5)
			$starStr .= '5.png';
		else
			$starStr .= '0_5.png';
		return $starStr; 
	}
}
?>