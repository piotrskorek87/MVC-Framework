<?php 

//Authenticate (only for authenticated users)
if($this->_getRoutes[$key]['auth'] == 'auth'){
	$auth = new Auth();
	if(!$auth->check()) {
		Redirect::to(Config::get('authentication/authenticate')); //login.index
	}
}

//Redirect if authenticated (only for guests)
if($this->_getRoutes[$key]['auth'] == 'guest'){
	$auth = new Auth();
	if($auth->check()) {
		Redirect::to(Config::get('authentication/redirect_if_authenticated')); //home.index
	}
}

?>