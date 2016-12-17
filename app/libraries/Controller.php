<?php 

class Controller{

	public function model($model){
		require_once'../app/models/'. $model .'.php';
		return new $model();
	}

	public function view($view, $data = []){
		$auth = new Auth;

		foreach($data as $k => $v) {
            $$k = $v;
        }

		if(!isset($data['errors'])){
			$errors = new Validate;
		}

		$templatingEngine = new TemplatingEngine;
		$templatingEngine->parse('../app/views/'. $view .'.php');

		include_once('tempTemplate.php');

	}
}

?>


