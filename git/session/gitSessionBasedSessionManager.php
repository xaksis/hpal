<?php
/**
 * A simple implementation for the SessionManager.
 */
class gitSessionBasedSessionManager implements gitSessionManager {
  private $config;
  private $accountService;

  public function __construct(gitConfig $config, gitAccountService $accountService) {
    $this->config = $config;
    $this->accountService = $accountService;
  }

  /**
   * Gets the logged in account in the current session.
   * @return mixed the logged in account or NULL if there is no account logged in.
   */
  public function getSessionAccount() {
	session_start();
    if (isset($_SESSION['email'])) {
      return $this->accountService->getAccountByEmail($_SESSION['email']);
    }
    return NULL;
  }

  /**
   * Saves the logged account information to the session and logs the user in. If parameter is NULL,
   * the account in the session should be removed.
   * @param mixed $account the account which should be logged in.
   */
  public function setSessionAccount($account) {
	session_start(); 
	if(empty($account))
	{
		unset($_SESSION['id']);
		unset($_SESSION['email']);
		unset($_SESSION['displayName']);
		unset($_SESSION['privilege']);
		unset($_SESSION['fullName']);
    }
	else
	{
		$_SESSION['id'] = $account->getUid();
		$_SESSION['email'] = $account->getEmail();
		$_SESSION['displayName'] = $account->getDisplayName();
		$_SESSION['privilege'] = $account->getPrivilege();
		$_SESSION['fullName'] = $account->getFullName(); 
	}
  }

  /**
   * Gets the IDP assertion for the request.
   * @return mixed the IDP assertion
   */
  public function getAssertion() {
	session_start();
    if (isset($_SESSION['idpAssertion'])) {
      return gitAssertion::fromString($_SESSION['idpAssertion']);
    }
    return NULL;
  }

  /**
   * Saves the IDP assertion information to the session. If parameter is NULL, the data in the
   * session should be cleared.
   * @param mixed $assertion the data to be saved.
   */
  public function setAssertion($assertion) {
	session_start();
    $_SESSION['idpAssertion'] = (string)$assertion;
  }
}
