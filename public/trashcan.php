	// public function get($table, $where) {
	// 	return $this->action('SELECT *', $table, $where);
	// }

	// public function first() {
	// 	return $this->results()[0];
	// }



	// public function delete($table, $where) {
	// 	return $this->action('DELETE', $table, $where);
	// }

	// public function insert($table, $fields = array()) {
	// 	if(count($fields)) {
	// 		$keys = array_keys($fields);
	// 		$values = '';
	// 		$x = 1;

	// 		foreach($fields as $field) {
	// 			$values .= '?';
	// 			if($x < count($fields)) {
	// 				$values .= ', ';
	// 			}

	// 			$x++;
	// 		}

	// 		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

	// 		if(!$this->query($sql, $fields)->error()) {
	// 			return true;
	// 		}
	// 	}
	// 	return false;
	// }

	// public function update($table, $id, $fields) {
	// 	$set = '';
	// 	$x = 1;

	// 	foreach($fields as $name => $value) {
	// 		$set .= "{$name} = ?";
	// 		if($x < count($fields)) {
	// 			$set .= ', ';			
	// 		}
	// 		$x++;
	// 	}

	// 	$sql = "UPDATE {$table} SET {$set} WHERE id = {$id} ";

	// 	if(!$this->query($sql, $fields)->error()) {
	// 		return true;
	// 	}

	// 	return false;
	// }

	// public function action($action, $table, $where = array()) {
	// 	if(count($where) === 3) {
	// 		$operators = array('=', '>', '<', '>=', '<=');

	// 		$field 		= $where[0];
	// 		$operator 	= $where[1];
	// 		$value 		= $where[2];

	// 		if(in_array($operator, $operators)) {
	// 			$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

	// 			if(!$this->query($sql, array($value))->error()) {
	// 				return $this;
	// 			}
	// 		}
	// 	}
	// 	return false;
	// }

		public function belongsToMany($pivot_table, $foreign_table){

		$local_key = $this->data()->id;

		$sql = "SELECT {$foreign_table}_id FROM {$pivot_table} WHERE {$this->table}_id = {$local_key}";

		$result

		$sql = "SELECT * FROM {$foreign_table} WHERE id = {$$$$$$$$result}";



		$sql = "SELECT local_table.{$foreign_table}_id , foreign_table.* FROM {$this->table} AS local_table
				LEFT JOIN {$foreign_table} AS foreign_table 
				ON local_table.{$foreign_table}_id = foreign_table.id 
				WHERE local_table.{$this->where}";

		if($query = $this->db->query($sql, $this->whereValues)){
			if($query->count()){
				$this->results = $query->results();
				$this->count = $query->count();
			}
		}

		return $this;
	}

	// public function belongsToOne($foreign_table){
	// 	$sql = "SELECT local_table.{$foreign_table}_id , foreign_table.* FROM {$this->table} AS local_table
	// 			LEFT JOIN {$foreign_table} AS foreign_table 
	// 			ON local_table.{$foreign_table}_id = foreign_table.id 
	// 			WHERE local_table.{$this->where}";

	// 	if($query = $this->db->query($sql, $this->whereValues)){
	// 		if($query->count()){
	// 			$this->results = $query->results();
	// 			$this->count = $query->count();
	// 		}
	// 	}

	// 	return $this;
	// }

	public function hasOne($foreign_table){
		$sql = "SELECT local_table.id, foreign_table.* FROM {$this->table} AS local_table
 				LEFT JOIN {$foreign_table} AS foreign_table 
 				ON local_table.id = foreign_table.{$this->table}_id
				WHERE local_table.{$this->where} LIMIT 1";

		if($query = $this->db->query($sql, $this->whereValues)){
			if($query->count()){
				$this->results = $query->results();
				$this->count = $query->count();
			}
		}

		return $this;
	}

	public function hasMany($foreign_table){
		$sql = "SELECT local_table.id, foreign_table.* FROM {$this->table} AS local_table
 				LEFT JOIN {$foreign_table} AS foreign_table 
 				ON local_table.id = foreign_table.{$this->table}_id
				WHERE local_table.{$this->where}";

		if($query = $this->db->query($sql, $this->whereValues)){
			if($query->count()){
				$this->results = $query->results();
				$this->count = $query->count();
			}
		}

		return $this;
	}


	public function create($fields = array()){
		if(count($fields)) {
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach($fields as $field) {
				$values .= '?';
				if($x < count($fields)) {
					$values .= ', ';
				}

				$x++;
			}
		}

		$sql = "INSERT INTO {$this->table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

		if(!$this->db->query($sql, $fields)->error()){
			return true;
		}
		return false;		
	}


	public function create($fields = array()){
		if(count($fields)) {
			$keys = array_keys($fields);
			$values = '';
			$x = 1;

			foreach($fields as $field) {
				$values .= '?';
				if($x < count($fields)) {
					$values .= ', ';
				}

				$x++;
			}
		}

		$sql = "INSERT INTO {$this->table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

		if(!$this->db->query($sql, $fields)->error()){
			return true;
		}
		return false;		
	}