<?php 

class HomeController extends Controller {

	public function getIndex(){
		$auth = new Auth;
		$user = new User;
		$status = new Status;
		if($auth->check()){

			$statuses = $status->orWhereIn(array('user_id', '='), $auth->user()->friends()->data())->get()->merge()->where(array('user_id', '=', $auth->user()->id))->get()->where(array('parent_id', '=', ''))->data(); //corect parrent_id = ''

			$this->view('timeline/index', ['statuses' => $statuses]);
		}else{
			$this->view('home/index');
		}
	}
}
?>


