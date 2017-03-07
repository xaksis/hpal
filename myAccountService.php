<?php
require_once(dirname(__FILE__) . '/gitAccountService.php');

/**
 * The account related operations that the relying party site needs to implement.
 */
class OpencartAccountService implements gitAccountService{
	private $registry;

  public function __construct($registry) {
    $this->registry = $registry;
  }

  /**
   * Given the email returns the account or NULL if the account doesn't exist.
   *
   * @param string $email the email to be checked
   * @return mixed the account object or NULL if not exist.
   */
  function getAccountByEmail($email){
  	$ret = NULL;
	
	
	
  	$db = $this->registry->get('db');
	  $customer_query = $db->query("SELECT * FROM "
	      . DB_PREFIX ."customer WHERE LOWER(email) = '"
	      . $db->escape(strtolower($email)) . "' AND status = '1'");
		if ($customer_query->num_rows) {
			if ($customer_query->row['type'] == 1) {
				$type = gitAccount::FEDERATED;
			} else {
				$type = gitAccount::LEGACY;
			}
			$ret = new gitAccount($customer_query->row['email'], $type);
			$ret->setLocalId($customer_query->row['customer_id']);
			$ret->setDisplayName($customer_query->row['firstname']);
		}
		return $ret;
  }

  /**
   * Returns true if the email and password pair is correct.
   *
   * @param string $email the user input email
   * @param string $password the user input password
   */
  function checkPassword($email, $password) {
  	$config = $this->registry->get('config');
  	$db = $this->registry->get('db');
  	$sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $db->escape(strtolower($email)) . "' AND status = '1";
		if (!$config->get('config_customer_approval')) {
			$sql .=  "' AND approved = '1'";
		}
		$customer_query = $db->query($sql);
		if ($customer_query->num_rows) {
			$isFederated = $customer_query->row['type'];
			if (!$isFederated) {
				return $db->escape(md5($password)) ==  $customer_query->row['password'];
			} else {
				return TRUE;
			}
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
  	$db = $this->registry->get('db');
    $query = $db->query("UPDATE " . DB_PREFIX 
        . "customer SET type = 1 WHERE email = '"
        . $db->escape($email) . "'");
    return TRUE;
  }
}
