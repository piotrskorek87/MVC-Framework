<?php 
require_once '../app/models/User.php';

class ProfileController extends Controller {

	public function getIndex($username){
		$user = new User;
		$auth = new Auth;

		$user = $user->where(array('username', '=', $username))->get()->first();

		if(!$user){
			echo 'Error, no user';
		}

		$statuses = $user->statuses()->where(array('parent_id', '=', ''))->data();

		$friends = $user->friends()->data();

		$this->view('profile/index', ['user' => $user, 'statuses' => $statuses, 'authUserIsFriend' => $auth->user()->isFriendsWith($user), 'friends' => $friends]);


	}
}