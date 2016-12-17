<?php 

require_once '../app/init.php';


// try {
// 	$handler = new PDO('mysql:host=' . Config::get('mysql/host') . '; dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
// 	$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch(PDOExeption $e) {
// 	die($e->getMessage());
// }

class Experiment{
	public 	$db,
			$whereValues = [],
			$results,
			$count,

			$id, $username, $password, $salt, $name, $joined, $group;

	public function __construct(){
		$this->db = Model::getInstance();
	}

 	public function profileLink(){
        // return sprintf('<a href="/profile/%s">%s</a>',$this->id,$this->username).' ';
        return "SELECT * FROM user WHERE id = {$this->id} AND username = {$this->username}". '</br>';
    }

	public function action(){

		$operations = array('SELECT * ', 'DELETE ');

		// if(in_array($operation, $operations)){

		// 	if($operation == 'DELETE ') {
		// 		$table = (!empty($this->foreignTable) ? $this->foreignTable : $this->table);
		// 		$sql = "{$operation} FROM {$table} WHERE {$this->where}";		
		// 	} else if($operation == 'SELECT * '){
		// 		$sql = "{$operation} FROM {$this->table} WHERE {$this->where}";	
		// 	}
		// }else{
		// 	$sql = $operation;
		// }

		// $sql = "SELECT * FROM user WHERE id = {$this->id}";
		$sql = "SELECT * FROM user";

		// $this->addResults = true;
		if($query = $this->db->query($sql, $this->whereValues)){
			if($query->count()){
				// if(!empty($this->temporaryResults)){
				// 	$this->results = array_merge($this->temporaryResults, $query->results());
				// 	$this->count = $this->count + $query->count();
				// }else{
					$this->results = $query->results();
					$this->count = $query->count();
				// }
			}
		}
		// $this->where = '';
		// $this->whereValues = [];
	}

	public function data(){
		return $this->results;
	}

	public function first(){
		return $this->data()[0];
	}

	public function count(){
		return $this->count;
	}


}

class Model{
	private static $_instance = null;
	private $_pdo,
			$_query, 
			$_error = false, 
			$_results, 
			$_count = 0;	

	private function __construct(){
		try {
			$this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . '; dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
			$this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOExeption $e) {
			die($e->getMessage());
		}
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new Model();
		}
		return self::$_instance;
	}

	public function query($sql, $params = array()){

		$this->_query = $this->_pdo->query("SELECT * FROM user");
		$this->_results = $this->_query->fetchAll(PDO::FETCH_CLASS, 'experiment');
		$this->_count = $this->_query->rowCount();



		// $this->_query->setFetchMode(PDO::FETCH_CLASS, 'experiment');
		// foreach($query as $user){
		//     echo $user->profileLink();
		// }


		// $this->_error = false;
		// if($this->_query = $this->_pdo->prepare($sql)) {
		// 	$x = 1;
		// 	if(count($params)) {
		// 		foreach($params as $param) {
		// 			$this->_query->bindValue($x, $param);
		// 			$x++;
		// 		}
		// 	}

		// 	if($this->_query->execute()) {

		// 	} else {
		// 		$this->_error = true;
		// 	}
		// }

		return $this;
	}

	public function action($action, $table, $where = array()) {
		if(count($where) === 3) {
			$operators = array('=', '>', '<', '>=', '<=');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)) {
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}
		return false;
	}

	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	public function results() {
		return $this->_results;
	}

	public function error() {
		return $this->_error;
	}

	public function count() {
		return $this->_count;
	}

	public function lastInsertId($name = NULL) {
    	return $this->_pdo->lastInsertId($name);
	}

	public function first() {
		return $this->results()[0];
	}
}

$experiment = new Experiment;

// $sql = "SELECT * FROM user";

$experiment->action();

// 'user', array($field, '=', $user)
// $model->get('user', array('id', '>', 0));

if($experiment->count()) {
	foreach ($experiment->data() as $person) {
			echo $person->username . '  first_loop ';
			$person->action();
			foreach ($person->data() as $nextResult) {
				echo $nextResult->profileLink();
			}
	}
}

// $hash = $model->first()->hash;


							
// 								$this->_db->insert('users_session', array(
// 									'user_id' => $this->data()->id,
// 									'hash' => $hash
// 								));
// 							} else {
// 								$hash = $hashCheck->first()->hash;












// while($r = $query->fetch()){

// }

// $result = $sth->fetchAll(PDO::FETCH_CLASS, "User");


?>

