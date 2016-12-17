<?php 

class Photo extends Model{

	public $table = 'photo';


	public function __construct(){
		parent::__construct();
	}	

	public function coment($coment){
		$this->polimorphic('comentable', 'user')->create(array(
															'coment' => $coment
															));
	}

	public function like(){
		$this->polimorphic('likeable', 'user')->create();
	}


}
?>