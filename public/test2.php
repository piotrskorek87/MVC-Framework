<?php 
require_once '../app/init.php';

require_once '../app/models/User.php';

require_once '../app/models/User_session.php';

$user = new User;

$auth = new Auth;

// $friends = $auth->user()->friends();

// $user = $user->where(array('id', '=', '4'))->get()->first();

$user = $user->where(array('id', '=', '16'))->get()->first();

// $us = 'peterson';

// $user2 = $user->where(array("CONCAT(first_name, ' ', last_name)", "LIKE", "%{$us}%"))->orWhere(array("username", "LIKE", "%{$us}%"))->get()->first();

// echo $user2->username;


// $user->statuses()->get();

$auth = new Auth();

// $remember = false;

// $auth->login('kate', 'pppppp', $remember);

// $auth->logout();

$status = new Status;

$status = $status->where(array('id', '=', '79'))->get()->first();

echo $status->id;


$results = $status->replies();

if($results){

	echo 'good';
	foreach($results as $result){
		echo $result->body. '<br/>';
	}	
}else{
	echo 'bad';
}



// $user = $status->user();

// echo $user->username;

// $results = $status->likes();

// echo $auth->user()->hasLikedStatus($status);

// echo $results->count();

// if($results){

// 	echo 'good';
// 	foreach($results as $result){
// 		echo $result->username. '<br/>';
// 	}	
// }else{
// 	echo 'bad';
// }

// echo $sql = "SELECT * FROM user WHERE CONCAT(first_name, ' ', last_name) LIKE peterson

// $sql = "SELECT * FROM user WHERE id = 4";
// $db->query($sql, array());

// if($user){

// 	echo 'good';
// 	foreach($user as $result){
// 		echo $result->username. '<br/>';
// 	}	
// }else{
// 	echo 'bad';
// }






















// "CONCAT(first_name, ' ', last_name)"

// $user3 = $user->where(array('id', '=', '3'))->get()->first();

// echo $user->hasFriendRequestPending($user2);

// echo $user->hasFriendRequestReceived($user2);


// $friends = $user->friends();

// // echo $auth->user()->getUsername();

// if($friends){
// 	echo 'results';
// 	foreach($friends->data() as $friend){
// 		echo  $friend->getUsername().  '<br/>';
// 	}
// }else{
// 	echo 'bad';
// }

// $user->acceptFriendRequest($user2);

// // $auth->logout();

// // $auth->