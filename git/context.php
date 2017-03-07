<?php
require_once(dirname(__FILE__) . '/git/handler/gitLoginHandler.php');
require_once(dirname(__FILE__) . '/git/util/gitConfig.php');
require_once(dirname(__FILE__) . '/git/util/gitApiClient.php');
require_once(dirname(__FILE__) . '/git/util/gitContext.php');
require_once(dirname(__FILE__) . '/data/myAccountService.php');
require_once(dirname(__FILE__) . '/session/gitSessionBasedSessionManager.php');

class ContextLoader{
	public static function load() {
    $config = new gitConfig();
	$accountService = new myAccountService();
    $config->setApiKey('AIzaSyARpZZaoEOl_NX8JR35F4Ah5BEjfwcEvyk');
    $config->sessionUserKey = 'id';
    $config->idpAssertionKey = 'idpAssertion';
    gitContext::setConfig($config);
    
    gitContext::setAccountService($accountService);
    gitContext::setSessionManager(new gitSessionBasedSessionManager($config, $accountService));
	}
}
?>
