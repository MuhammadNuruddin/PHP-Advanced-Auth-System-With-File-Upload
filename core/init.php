<?php 

session_start();

$GLOBALS['config'] = array(

	'mysql' => array(
		'host' => '127.0.0.1',
		'username' => 'root',
		'password' => '',
		'db' => 'logreg'

	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(
		'session_name' => 'user',
		'token_name' => 'token'
	)

);



spl_autoload_register(function($class){
	require_once 'classes/'.$class.'.php';
});



require_once 'functions/sanitize.php';
require_once 'functions/error_check.php';
require_once 'functions/check_array.php';


if (Cookie::exists(Config::get('remember.cookie_name')) && !Session::exists(Config::get('session.session_name'))) {
	$hash = Cookie::get(Config::get('remember.cookie_name'));
	$hash_check = DB::connect()->select('users_session', array('hash', '=', $hash));
	if ($hash_check->count()) {
		$user = new User($hash_check->first()->user_id);
		$user->login();
	}
}