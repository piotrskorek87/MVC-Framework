<?php 

require_once 'functions/sanitize.php';
require_once 'functions/route.php';
require_once '../app/libraries/Model.php';
require_once '../app/models/User_session.php';

session_start();

$GLOBALS['config'] = array(
	'mysql' => array(
		'host' =>'localhost',
		'username' => 'root',
		'password' => '',
		'db' => 'mvc'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	),
	'authentication' => array(
		'authenticate' => '../auth/signin',
		'redirect_if_authenticated' => '../home/index'
	)
);

spl_autoload_register(function($class) {
	require_once 'libraries/' .$class . '.php';
});

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
	$hash = Cookie::get(Config::get('remember/cookie_name'));
	$hashCheck = DB::getInstance()->get('user_session', array('hash', '=', $hash));

	if($hashCheck->count()) {
		$Auth = new Auth($hashCheck->first()->user_id);
		$Auth->login();
	}
}

?>