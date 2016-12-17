<?php 

require_once '../app/init.php';

require_once '../app/models/User.php';

$auth = new Auth;

echo $auth->user()->id;

$user = new User;

$user->where(array('id', '=', '4'))->get();

$user = $user->first();


	$auth->user()->deleteFriend($user);
