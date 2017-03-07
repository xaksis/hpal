<?php

/**
 * The configuration for the relying party site customization.
 */
$gitConfig = array(
  // The API key in the Google API console.
  'apiKey' => 'AIzaSyARpZZaoEOl_NX8JR35F4Ah5BEjfwcEvyk',
  // The default URL after the user is logged in.
  'homeUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/hp/home.php',
  // The user signup page.
  'signupUrl' => 'http://'.$_SERVER['HTTP_HOST'].'/hp/home.php',
  // Scan the these absolute directories when finding the implementations e.g. account service and
  // session manager. The multiple directories should be separated by a ,
  'externalClassPaths' => 'logic,util,session,data',
  // The class name that implements the gitAccountService interface. You can also set the
  // implementation instance by leaving it empty and invoking the setter method in the gitContext
  // class. NOTE: The class name should be the same as the file name without the '.php' suffix.
  'accountService' => 'myAccountService',
  // The class name that implements the gitSessionManager interface. Same as the account service,
  // there is a setter method in the gitContext class. NOTE: the class name should be the same as
  // the file name without the '.php' suffix.
  'sessionManager' => 'gitSessionBasedSessionManager',
);
