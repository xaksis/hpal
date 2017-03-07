<?php

//restaurant object 
class Dish{

	public $name; 
	public $restaurant_id;
	public $user_id;
	public $rating;
	public $noOfRatings; 
	public $description;
	
	function __construct()
	{
		$this->name = 'NOT_SET'; 
		$this->description = 'NOT_SET';
		$this->rating = 0;
	}
}

?>