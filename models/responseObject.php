<?php

//response object returned from functions
//returns error code and a string explanation
class ResponseObject{
	public $code;
	public $explanation;
	
	public function formatDate($sql_date)
	{
		$date=strtotime($sql_date);
		$final_date=date("M j, Y", $date);
		return $final_date;
	}
	
	//function to sanitize text
	public function sanitize($str)
	{
		//string must have data
		if(empty($str)) return false; 
		
		//remove trailing and leading white spaces
		$str = trim($str);	
		
		//html special characters should be converted
		$str = htmlspecialchars($str, ENT_QUOTES);
		
		//add slashes to whatever's left
		$str = addslashes($str);
		
		//if php doesn't strip slashes 
		//automatically, strip them
		if(get_magic_quotes_gpc())
			$str = stripslashes($str);
		
		//remove html tags
		$str = strip_tags($str); 
		
		return $str; 
	}
	
	//function to publish a like button
	function likeButton($url_to_like, $action = "like")
	{
		echo "<fb:like send='true' href='$url_to_like' layout='button_count' width='200' show_faces='false' action='like' font='arial'></fb:like>"; 
	}
}

?>