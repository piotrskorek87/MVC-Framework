<?php 



class User_session extends Model{

	public $table = 'user_session';


	public function __construct(){
		parent::__construct();
	}	

	public function test(){
		echo $this->user_id.'Love God, creator of the universe';
	}	

}
?>