<?php
/**
 * The logic to handle the email and password post request.. It collects the input params and the
 * current account status then make decision to call the action class to generate the response.
 */

class gitPasswordLogic {
  private $action;
  public function __construct($action) {
    $this->action = $action;
  }

  public function run($request, $response) {
    $email = $request->getEmail();
    $password = $request->getPassword();

    if (empty($email) || !gitUtil::isValidEmail($email)) {
      $this->action->sendEmailNotExist($request, $response);
    }

    $account = gitContext::getAccountService()->getAccountByEmail($request->getEmail());
    if (empty($account)) {
      $this->action->sendEmailNotExist($request, $response);
    } else {
      $request->setAccount($account);
      if ($account->getAccountType() == gitAccount::FEDERATED) {
        $this->action->sendFederated($request, $response);
      } else {
        if (gitContext::getAccountService()->checkPassword($email, $password)) {
          $this->action->login($request, $response);
          $this->action->sendOk($request, $response);
        } else {
          $this->action->sendPasswordError($request, $response);
        }
      }
    }
  }
}
