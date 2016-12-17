<?php 

class Route{

	protected $controller = 'home';
	protected $method = 'index';
	protected $params = [];

	private $_getRoutes = [];
	private $_postRoutes = [];	

	public function get($uri, $params = []){
		
		$route = trim($uri, '/');

		if(isset($params['uses'])){
			$paramsArray = explode('@', $params['uses']);
			$controller = $paramsArray[0];
			$method = $paramsArray[1];
		}

		(isset($params['auth'])) ? $auth = $params['auth'] : $auth = false;	
		(isset($params['csrf'])) ? $csrf = $params['csrf'] : $csrf = false;

		$getArray = [$route => [
			'controller' => $controller,
			'method' => $method,
			'auth' => $auth,
			'csrf' => $csrf,
			]
		];

		$this->_getRoutes = array_merge($this->_getRoutes, $getArray);
	}

	public function post($uri, $params = []){
		
		$route = trim($uri, '/');

		if(isset($params['uses'])){
			$paramsArray = explode('@', $params['uses']);
			$controller = $paramsArray[0];
			$method = $paramsArray[1];
		}

		(isset($params['auth'])) ? $auth = $params['auth'] : $auth = false;	
		(isset($params['csrf'])) ? $csrf = $params['csrf'] : $csrf = false;

		$postArray = [$route => [
			'controller' => $controller,
			'method' => $method,
			'auth' => $auth,
			'csrf' => $csrf,
			]
		];

		$this->_postRoutes = array_merge($this->_postRoutes, $postArray);				
	}

	public function submit(){

		$uri = isset($_GET['uri']) ? $_GET['uri'] : '/';
		$uriParams = explode('/', $uri);
		$uri = $uriParams[0].'/'.$uriParams[1];
		unset($uriParams[0]);
		unset($uriParams[1]);

		$this->params = $uriParams ? array_values($uriParams) : []; //check that 

		if(empty($_POST)){
			foreach ($this->_getRoutes as $key => $value) {
				if($key == $uri){

					$controllerPath = $this->_getRoutes[$key]['controller'];
					$controller = end(explode('/', $controllerPath));


					if(file_exists('../'. $controllerPath .'.php')){	
						$this->controller = $controller;
					}

					require_once '../'. $controllerPath .'.php';


					$this->controller = new $this->controller;

					if($this->_getRoutes[$key]['csrf'] == 'true'){
						require_once'../app/middleware/csrf.php';
					}	

					require_once'../app/middleware/authentication.php';

					if(method_exists($this->controller,  $this->_getRoutes[$key]['method'])){
						$this->method = $this->_getRoutes[$key]['method'];
					}				

					call_user_func_array([$this->controller, $this->method], $this->params);
				}
			}
		}

		if(!empty($_POST)){
			foreach ($this->_postRoutes as $key => $value) {
				if($key == $uri){

					$controllerPath = $this->_postRoutes[$key]['controller'];
					$controller = end(explode('/', $controllerPath));


					if(file_exists('../'. $controllerPath .'.php')){	
						$this->controller = $controller;
					}

					require_once '../'. $controllerPath .'.php';


					$this->controller = new $this->controller;

					if($this->_postRoutes[$key]['csrf'] == 'true'){
						require_once'../app/middleware/csrf.php';
					}	

					require_once'../app/middleware/authentication.php';

					if(method_exists($this->controller,  $this->_postRoutes[$key]['method'])){
						$this->method = $this->_postRoutes[$key]['method'];
					}				

					call_user_func_array([$this->controller, $this->method], $this->params);
				}
			}
		}
	}
}

?>
