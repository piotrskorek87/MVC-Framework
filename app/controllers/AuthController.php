<?php 

class AuthController extends Controller {

	public function getSignup(){
		$this->view('auth/signup');
	}

	public function postSignup(){
		$validate = new Validate();
		$user = $this->model('User');

		if(Input::exists()) {
			$validation = $validate->check($_POST, array(
													'username' => array(
														'required' => true,
														'min' => 2,
														'max' => 20,
														'unique' => 'user'

													),
													'password' => array(
														'required' => true,
														'min' => 6
													),
													'password_again' => array(
														'required' => true,
														'matches' => 'password'
													),
													'name' => array(
														'required' => true,
														'min' => 2,
														'max' => 50
													)
												));

			if(!$validate->passed()){
				$this->view('auth/signup', ['errors' => $validate]); //validate->errors();
			}

			if($validate->passed()){

				$salt = Hash::salt(32);

				$user->create(array(
							'username' => Input::get('username'),
							'password' => Hash::make(Input::get('password'), $salt),
							'salt' => $salt,
							'first_name' => Input::get('name'),
							'group' => '1'
							));
			}
		}		
	}

	public function getSignin(){
		$this->view('auth/signin');
	}

	public function postSignin(){
		$auth = new Auth;
		$validate = new Validate();

		if(Input::exists()) {

			$validation = $validate->check($_POST, array(
													'username' => array(
														'required' => true,
														'min' => 2,
														'max' => 20
													),
													'password' => array(
														'required' => true,
														'min' => 6
													)
												));

			if(!$validate->passed()){
				$this->view('auth/signin', ['errors' => $validate]);
			}

			if($validate->passed()){
				$remember = (Input::get('remember') === 'on') ? true : false;
				$login = $auth->login(Input::get('username'), Input::get('password'), $remember);

				if($login) {
					Session::flash('info', 'You are now logged in');
					Redirect::to('../home/index');
				} else {
					Session::flash('info', 'Could not sign you in with those details');
					Redirect::to('../auth/signin');
				}
			}
		}		
	}

	public function getSignOut(){
		$auth = new Auth();
		$auth->logout();
		Session::flash('info', 'You are now logged out');
		Redirect::to('signin');
	}
}