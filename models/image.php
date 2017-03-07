<?php

//restaurant object 
class Image{

	public $path_to_thumb; 
	public $path_to_orig;
	public $path_to_reg;
	public $caption;
	public $comment;
	public $confirmed; 
	public $user_id;
	public $dish_id;
	public $restaurant_id;
	
	function __construct()
	{
		$this->path_to_thumb = 'NOT_SET';
		$this->path_to_orig = 'NOT_SET';
		$this->path_to_reg = 'NOT_SET';
		$this->caption = 'NOT_SET';
		$this->comment = 'NOT_SET';
		$this->confirmed = 0;
	}
}

?>