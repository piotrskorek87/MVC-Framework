<?php 

require_once '../app/init.php';

$route = new Route();

$route->get('/home/index', [
	'uses' => 'app/controllers/HomeController@getIndex',
	'csrf' => 'true',
	'auth' => 'auth',
]);

$route->get('/auth/signup', [
	'uses' => 'app/controllers/AuthController@getSignup',
	'auth' => 'guest',
]);

$route->post('/auth/signup', [
	'uses' => 'app/controllers/AuthController@postSignup',
	'csrf' => 'true',
	'auth' => 'guest',
]);

$route->get('/auth/signin', [
	'uses' => 'app/controllers/AuthController@getSignin',
	'auth' => 'guest',
]);

$route->post('/auth/signin', [
	'uses' => 'app/controllers/AuthController@postSignin',
	'csrf' => 'true',
	'auth' => 'guest',
]);

$route->get('/auth/signout', [
	'uses' => 'app/controllers/AuthController@getSignout',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->get('/friend/index', [
	'uses' => 'app/controllers/FriendController@getIndex',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->get('/friend/add', [
	'uses' => 'app/controllers/FriendController@getAdd',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->get('/friend/accept', [
	'uses' => 'app/controllers/FriendController@getAccept',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->post('/friend/delete', [
	'uses' => 'app/controllers/FriendController@postDelete',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->get('/profile/index', [
	'uses' => 'app/controllers/ProfileController@getIndex',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->get('/search/results', [
	'uses' => 'app/controllers/SearchController@getResults',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->post('/status/post', [
	'uses' => 'app/controllers/StatusController@postStatus',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->post('/status/reply', [
	'uses' => 'app/controllers/StatusController@postReply',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

$route->get('/status/like', [
	'uses' => 'app/controllers/StatusController@getLike',
	// 'csrf' => 'true',
	// 'auth' => 'guest',
]);

// $route->post('/home/index', [
// 	'uses' => 'app/controllers/HomeController@postIndex',
// ]);

// $route->get('/home/check', [
// 	'uses' => 'app/controllers/HomeController@getCheck',
// 	'csrf' => 'false',
// 	'auth' => 'guest',
// ]);


$route->submit();

// echo '<pre>';
// print_r($route);

?>