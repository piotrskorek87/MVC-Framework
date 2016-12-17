<?php 
require_once '../app/models/User.php';

class SearchController extends Controller {

	public function getResults(){

		$user = new User;
		$query = Input::get('query');

		if(!$query){
			Redirect::to('../home/index');
		}

		$users = $user->where(array("CONCAT(first_name, ' ', last_name)", "LIKE", "%{$query}%"))->orWhere(array("username", "LIKE", "%{$query}%"))->get()->data();

		$this->view('search/results', ['users' => $users]);

	}
}