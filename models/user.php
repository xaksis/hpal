<?php

//restaurant object 
class User{

	public $username; 
	public $password;
	public $privilege;
	public $firstName;
	public $lastName; 
	public $email; 
	
	function __construct()
	{
		$this->username = 'NOT_SET'; 
		$this->password = 'NOT_SET'; 
		$this->privilege = 0; 
		$this->firstName = 'NOT_SET'; 
		$this->lastName = 'NOT_SET';
		$this->email = 'NOT_SET';
	}
}

?>