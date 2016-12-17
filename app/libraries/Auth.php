<?php 

require_once'../app/models/User.php';
require_once'../app/models/User_session.php';

class Auth{

	public  $user,
			$user_session;			

	private $_data,
			$_sessionName,
			$_cookieName,
			$_isLoggedIn;

	public function __construct($user = null){

		$this->user = new User;	
		$this->user_session = new User_session;	

		$this->_sessionName = Config::get('session/session_name');
		$this->_cookieName = Config::get('remember/cookie_name');

		if(!$user){
			if(Session::exists($this->_sessionName)){
				$user = Session::get($this->_sessionName);

				if($this->user->find($user)){
					$this->_data = $this->user->first();
					$this->_isLoggedIn = true;
				} 
			}
		} else{
			$this->user->find($user);
			$this->_data = $this->user->first();
		}
	}

	public function login($username = null, $password = null, $remember = false){
		if(!$username && !$password  && $this->exists()){
			Session::put($this->_sessionName, $this->user->first()->id);
		} else {
			$user = $this->user->find($username);
			
			if($user){
				$this->_data = $this->user->first(); //maybe not necessary
				if($this->user()->password === Hash::make($password, $this->user()->salt)){ //remove () 
					Session::put($this->_sessionName, $this->user()->id);

					if($remember){
						$hash = Hash::unique();
						$this->user_session->where(array('user_id', '=', $this->user()->id))->get();	//check that out

						if(!$this->user_session->count()){
							$this->user_session->create(array(
								'user_id' => $this->user()->id,
								'hash' => $hash
							));
						} else{
							$hash = $this->user_session->first()->hash;
						}
						Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
					}
					return true;
				}
			}
		}
		return false;
	}

	public function logout(){
		$this->user_session->where(array('user_id', '=', $this->user()->id))->delete();
		Session::delete($this->_sessionName);
		Cookie::delete($this->_cookieName);
	}

	public function update($fields = array()){ 
		if($this->Check()){
			$id = $this->user()->id;
		}
		if(!$this->user->where(array('id', '=', $id))->update($fields)){
			throw new Exception('There was a problem updating.');
		}
	}

	public function exists(){
		return(!empty($this->_data)) ? true : false;
	}

	public function user(){
		return $this->_data;
	}

	public function check(){
		return $this->_isLoggedIn;
	}

	// public function user(){
	// 	return $this->user;
	// }

}

?>