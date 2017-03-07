<?php
require_once(dirname(__FILE__) . '/gitAccountService.php');
require_once(dirname(__FILE__) . '/../../models/userModel.php');
//echo dirname(__FILE__) . '/../../models/userModel.php';
/**
 * The account related operations that the relying party site needs to implement.
 */
class myAccountService implements gitAccountService{
	
  public function __construct() {
  }
  /**
   * Given the email returns the account or NULL if the account doesn't exist.
   *
   * @param string $email the email to be checked
   * @return mixed the account object or NULL if not exist.
   */
  function getAccountByEmail($email){
  	$ret = NULL;
	$um = new UserModel(); 
	$user; 
	$response = $um->userStatus($email, $user);
	if($response->code)
	{
		$type; 
		if($user['type']==1)
			$type = gitAccount::FEDERATED;
		else
			$type = gitAccount::LEGACY; 
		$ret = new gitAccount($user['email'], $type); 
		$ret->setUid($user['id']);
		$ret->setDisplayName($user['username']);
		$ret->setPrivilege($user['privilege']);
		$ret->setFullName($user['fullname']);
	}	
	return $ret;
  }

  function addUser($user){ 
  	$um = new UserModel(); 
	$response = $um->CreateUserNew($user);
  }
  
  /**
   * Returns true if the email and password pair is correct.
   *
   * @param string $email the user input email
   * @param string $password the user input password
   */
  function checkPassword($email, $password) {
	$user; 
  	$um = new UserModel();
	$response = $um->userStatus($email, $user);
	if($user['type']==1) //federated
		return TRUE;
	else{ //if password matched
		$response = $um->loginUser($email, $password, $user);
		if($response->code==1) //if password matched
			return TRUE;
	}
	return FALSE;
  }
  
  /**
   * Given the email upgrade the corresponding account to use federated login. The password of the
   * account should be removed.
   *
   * @param string $email the ID for the account to be upgraded
   * @return bool true if the operation succeeds.
   */
  function toFederated($email){
	$user; 
  	$um = new UserModel(); 
	$response = $um->upgradeToFederated($email);
	return TRUE; 
  }
}
