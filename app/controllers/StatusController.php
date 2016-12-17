<?php 
require_once '../app/models/User.php';

class StatusController extends Controller {

	public function postStatus(){

		$validate = new Validate();
		$user = $this->model('User');
		$auth = new Auth;

		if(Input::exists()) {
			$validation = $validate->check($_POST, array(
													'status' => array(
														'required' => true,
														'max' => 1000,
													)
												));

			if($validate->passed()){
				echo 'passed';
				// $this->view('profile/index', ['validate' => $validate]);
			}

			$auth->user()->statuses()->create(array(
											'body' => Input::get('status'),
											'created_at' => '2016-02-14 11:11:11', //correct that php get timestamp
											));

			Session::flash('info', 'status posted.');
			Redirect::to('../home/index');		
		}
	}

	public function postReply($statusId){

		$validate = new Validate();
		$user = $this->model('User');
		$status = $this->model('status');
		$auth = new Auth;


		if(Input::exists()) {
			$validation = $validate->check($_POST, array(
													"reply-{$statusId}" => array(
															'required' => true,
															'max' => 1000,
													)
												));

			if($validate->passed()){
				echo 'passed';
				// $this->view('profile/index', ['validate' => $validate]);
			}

			$status = $status->where(array('id', '=', $statusId))->get()->first();

			if(!$status){
				Redirect::to('../../home/index');
			}

			if(!$auth->user()->isFriendsWith($status->user()) && $auth->user()->id !== $status->user()->id){ //correct that
				Redirect::to('../../home/index');
			}

			$auth->user()->statuses()->create(array(
											'body' => Input::get("reply-{$statusId}"),
											'parent_id' => $statusId,
											'created_at' => '2016-02-14 11:11:11',
											));

			Session::flash('info', 'reply posted.');
			Redirect::to('../../home/index');		
		}
	}

	public function getLike($statusId){
		$status = new Status;
		$auth = new Auth;
		$status = $status->where(array('id', '=', $statusId))->get()->first();

		if(!$status){
			Redirect::to('../../home/index');
		}

		if(!$auth->user()->isFriendsWith($status->user())){
			Redirect::to('../../home/index');
		}

		if($auth->user()->hasLikedStatus($status)){
			Redirect::to('../../home/index');
		}

		$auth->user()->likeStatus($status->id);
			Redirect::to('../../home/index');
	}
}

			