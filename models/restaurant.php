<?php

//restaurant object 
class Restaurant{

	public $id; 
	public $name; 
	public $address;
	public $city;
	public $state;
	public $zipcode;
	public $country;
	public $phone;
	public $genre;
	public $area; 
	public $adminRating;
	public $userRating;
	public $noOfRatings; 
	public $lat;
	public $lon; 
	public $imgCount; 
	
	function __construct()
	{
		$this->name = 'NOT_SET'; 
		$this->address = 'NOT_SET'; 
		$this->city = 'NOT_SET'; 
		$this->state = 'NOT_SET'; 
		$this->zipcode = 'NOT_SET'; 
		$this->country = 'NOT_SET'; 
		$this->phone = 'NOT_SET'; 
		$this->genre = 'NOT_SET'; 
		$this->area = 'NOT_SET';
		$this->adminRating = 0; 
		$this->userRating = 0;
		$this->noOfRatings = 0; 
		$this->lat = 'NOT_SET';
		$this->lon = 'NOT_SET';
		$this->imgCount = 0; 
	}
}

?>