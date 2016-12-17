<?php 
require_once '../app/models/User.php';

class FriendController extends Controller {

	public function getIndex(){
		$auth = new Auth;
		$friends = $auth->user()->friends()->data();

		$friendRequests = $auth->user()->friendRequests()->data();
		$this->view('friend/index', ['friends' => $friends, 'friendRequests' => $friendRequests]);
	}

	public function getAdd($username){
		$user = new User;
		$auth = new Auth;
		$user = $user->where(array('username', '=', $username))->get()->first();

		if(!$user){
			Session::flash('info', 'That user could not be found');
			Redirect::to('../../home/index');
		}

		if($auth->user()->id === $user->id){
			Redirect::to('../../home/index');
		}
	
		if($auth->user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending($auth->user())){
			Session::flash('info', 'Friend request already pending');
			Redirect::to('../../home/index');
		}
	
		if($auth->user()->isFriendsWith($user)){
			Session::flash('info', 'You are already friends.');
			Redirect::to('../../home/index');
		}
		$auth->user()->addFriend($user);
			Session::flash('info', 'Friend request sent.');
			Redirect::to('../../home/index');
	}

	public function getAccept($username){
		$user = new User;
		$auth = new Auth;
		$user = $user->where(array('username', '=', $username))->get()->first();

		if(!$user){
			Session::flash('info', 'That user could not be found');
			Redirect::to('../../home/index');
		}
 		if(!$auth->user()->hasFriendRequestReceived($user)){
			Redirect::to('../../home/index');
		}
		$auth->user()->acceptFriendRequest($user);
			Session::flash('info', 'Friend request accepted');
			Redirect::to('../../home/index');             //work on this
	}

	public function postDelete($username){
		$user = new User;
		$auth = new Auth;
		$user = $user->where(array('username', '=', $username))->get()->first();

		if(!$auth->user()->isFriendsWith($user)){
			Redirect::to('../../home/index'); 
		}

		$auth->user()->deleteFriend($user);

		Session::flash('info', 'Friend deleted');
		Redirect::to('../../home/index'); 
	}

}