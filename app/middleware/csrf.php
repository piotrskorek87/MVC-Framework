<?php 

if(Input::exists()){
	if(!Token::check(Input::get(Config::get('session/token_name')))){
		redirect::to('token_mismatch_exeption.php');
	}
}

?>