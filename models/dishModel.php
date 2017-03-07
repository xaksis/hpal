<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dish.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/dishDB.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/hp/models/responseObject.php';

class DishModel{
	//function to create a new user
	public function createOrUpdateDish($dish)
	{
		$db = new DishDb(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->insert_dish($dish);
		//
		if($response->explanation == 'dish already Exists')
			$response = $db->update_rating($response->code, $dish);
			
		return $response;  
	}
	
	//function to create a new user
	public function addScrapedDish($name, $desc, $price, $features, $restId, $connection)
	{
		$db = new DishDb(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->insert_scraped_dish($name, $desc, $price, $restId, $connection);
		echo $response->explanation, "<br/>";
		//once inserted we need to update features
		//get feature id first
		foreach($features as $feature)
		{
			$fResp = $this->getFeatureIdForName($feature, $connection);
			if($response->code != 0)
			{	 
				$uResp = $db->update_dish_feature($response->code, $fResp->code, $connection);
				echo $uResp->explanation, "<br/>";
			}
		}
		return $response;  
	}
	
	//function to get feature id given str name
	public function getFeatureIdForName($fName, $connection)
	{
		$db = new DishDb(); //create a DB class
		$response = new ResponseObject(); //a response object
		
		$response = $db->get_feature_id_for_name($fName, $connection);
		return $response;
	}
	
	//function to delete user
	public function deleteDish($id)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->delete_dish($id);
		return $response; 
	}
	
	public function createDishReview($id, $dish, $comment)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->insert_dishReview($id, $dish, $comment);
		return $response;
	}
	
	//get dishes for restaurant, also gets the image thumbnail
	//and rating
	public function getDishesForRestaurant($restId, $start, $n, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_n_dishes_for_restaurants($restId, $start, $n, $dishes);
		return $response;
	}
	
	//get dishes for restaurant, also gets the image thumbnail
	//and rating
	public function getDishesForRestaurantName($restName, $start, $n, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_n_dishes_given_restaurant_name($restName, $start, $n, $dishes);
		return $response;
	}
	
	//get dishes for restaurant, also gets the image thumbnail
	//and rating
	public function getAutoCompleteDishesForRestaurantName($restName, $sstr, $start, $n, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_n_dishes_given_restaurant_name_and_dish_str($restName, $sstr, $start, $n, $dishes);
		return $response;
	}
	
	//function to get overall top n dishes from db
	public function getTopDishes($n, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_top_n_dishes($n, $dishes);
		return $response;
	}
	
	//function to get dish search result
	public function getSearchDishes($s_str, $start, $n, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_dishes_for_search($s_str, $start, $n, $dishes);
		return $response;
	}
	
	//function to get n top restaurants for a dish
	public function getTopRestaurantsForDish($n, $str, &$rests)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_top_n_restaurants_for_dish($n, $str, $rests);
		return $response;
	}
	
	//function to get all restaurants for a dish
	public function getAllRestaurantsForDish($dishName, &$rests)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_all_restaurants_for_dish($dishName, $rests);
		return $response;
	}
	
	//function to get n images for a dish
	public function getImagesForDish($n, $dishId, &$images)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_n_images_for_dish($n, $dishId, $images);
		return $response;
	}
	
	//function to get generic dishes 
	public function getGenericDishes(&$gDishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_gDishes($gDishes);
		return $response;
	}
	
	public function getGenericDishById($id, &$row)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_gDish_by_id($id, $row);
		return $response;
	}
	
	public function updateGenericDish($id, $desc, $family)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->update_gDish($id, $desc, $family);
		return $response;
	}
	
	public function insertGenericDish($name, $desc, $family)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->insert_gDish($name, $desc, $family);
		return $response;
	}
	
	public function addGenericToDish($d_id, $gd_id)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->add_generic_to_dish($d_id, $gd_id);
		return $response;
	}
	
	//function to get dish given id
	public function getDishById($id, &$row)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_dish_by_id($id, $row);
		return $response;
	}
	
	//function to get dishes given generic dish id
	public function getDishesByGdishId($start, $n, $gd_id, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_dishes_by_gDish_id($start, $n, $gd_id, $dishes);
		return $response;
	}
	
	//function to get dishes given generic dish id
	public function getNewGdishes($start, $n, &$gDishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_new_gDishes($start, $n, $gDishes);
		return $response;
	}
	
	//function to get dishes given dish family name
	public function getDishesByfamily($start, $n, $family, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_n_dishes_given_dish_family($start, $n, $family, $dishes);
		return $response;
	}
	
	//function to get search 
	public function getAutocompleteNames($s_str, $start, $n, &$names)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_autocomplete_names($s_str, $start, $n, $names);
		return $response;
	}
	
	//function to get dish description
	public function getDishDescription($did, &$desc)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		
		$response = $db->get_dish_description($did, $desc);
		return $response;
	}
	
	//function to get dish categories given a restuarant
	public function getMenuCategoriesForRestaurant($restId, &$dcats)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		$response = $db->get_dish_categories_for_restaurant($restId, $dcats);
		return $response;
	}
	
	//function to get dishes given a category and restId
	public function getRestuarantDishesForGivenFeature($restId, $featureId, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		$response = $db->get_restaurant_dishes_for_given_feature($restId, $featureId, $dishes);
		return $response;
	}
	
	//function to get dishes that don't belong to a category
	public function getUncategorizedRestuarantDishes($restId, &$dishes)
	{
		$db = new DishDb();
		$response = new ResponseObject(); 
		$response = $db->get_uncategorized_restaurant_dishes($restId, $dishes);
		return $response;
	}
}
?>