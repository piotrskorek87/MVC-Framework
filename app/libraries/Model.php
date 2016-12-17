<?php 
class Model{

	public 	$db, $sql,
			$foreignTable, $pivotTable, $polimorphicTable,
			$foreignKey, $localKey,
			$relationship,
			$pivot = false,

			$where = '',
			$whereValues = [],
			$results = [];

	public function __construct(){
		$this->db = DB::getInstance();
	}

////////////////////////////////////////////////COLLECT PARAMETER DATA METHODS///////////////////////////////////////////////////////

	public function where($where = array()){ 
		if(count($where) === 3){
			$operators = array('=', '>', '<', '>=', '<=', 'LIKE');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];	

			if(in_array($operator, $operators)){				
				if(!empty($this->db->results)){
					foreach ($this->db->results() as $result) {
						if($result->$field !== $value){ 
							unset($this->db->results()->result);    
						}
					}			
				}else{
					if(!empty($this->where)){
						$this->where .= " AND";   
					}
					$this->where .= " {$field} {$operator} ?";
					$this->whereValues[] = $value;
				}		
				return $this;
			}
		}
	}	

	public function orWhere($where = array()){
		if(count($where) === 3){
			$operators = array('=', '>', '<', '>=', '<=', 'LIKE');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)){
				if(!empty($this->where)){
					$this->where .= " OR";   
				}
				$this->where .= " {$field} {$operator} ?";
				$this->whereValues[] = $value;
			}
		}
		return $this;
	}

	public function wherePivot($where = array()){
		if(count($where) === 3){
			$operators = array('=', '>', '<', '>=', '<=', 'LIKE');

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)){
				$this->sql .= " AND pivot_table.{$field} {$operator} ?";
				$this->whereValues[] = $value;
			}
		}
		return $this;
	}

	public function orWhereIn($where = array(), $objects){
		if(count($where) === 2){
			if($objects){
				foreach ($objects as $object) {

					$operators = array('=', '>', '<', '>=', '<=', 'LIKE');

					$field 		= $where[0];
					$operator 	= $where[1];
					$value 		= $object->id;

					if(in_array($operator, $operators)){
						if(!empty($this->where)){
							$this->where .= " OR";   
						}
						$this->where .= " {$field} {$operator} ?"; 
						$this->whereValues[] = $value;
					}
				}
			}
			return $this;
		}
	}

	public function pivot(){
		$this->pivot = true;
		return $this;
	}

	public function merge(){
		$this->results = array_merge($this->results, $this->db->results());	
		$this->db->results = [];		
		return $this;
	}

//////////////////////////////////////////////////////GET HELPER METHODS/////////////////////////////////////////////////////////////

	public function action($sql){
		$table = !empty($this->foreignTable) ? $this->foreignTable : $this->table;

		$this->db->query($sql, $table, $this->whereValues);		
		
		$this->where = '';
		$this->whereValues = [];
		$this->sql = '';
	}	

	public function relationalshipQueryBuilder($relationship, $foreignTable = null, $pivotTable = null, $foreignKey = null, $localKey = null, $polimorphicTable = null){
		$this->foreignTable = $foreignTable;
		$this->pivotTable = $pivotTable;
		$this->polimorphicTable = $polimorphicTable;
		$this->foreignKey = $foreignKey;
		$this->localKey = $localKey;

		$query1 =  "SELECT local_table.id, foreign_table.* FROM {$this->table} AS local_table
					RIGHT JOIN {$this->foreignTable} AS foreign_table 
					ON local_table.id = foreign_table.{$this->foreignKey}
					WHERE local_table.id = {$this->id}"; 

		$query2 =  "SELECT local_table.id, pivot_table.*, foreign_table.* FROM {$this->table} AS local_table
					LEFT JOIN {$this->pivotTable} AS pivot_table
					ON pivot_table.{$this->foreignKey} = local_table.id 
					RIGHT JOIN {$this->foreignTable} AS foreign_table 
					ON foreign_table.id = pivot_table.{$this->localKey} 
					WHERE local_table.id = {$this->id}"; 

		$query3 =  "SELECT local_table.{$this->foreignKey}, foreign_table.* FROM {$this->table} AS local_table
 					RIGHT JOIN {$this->foreignTable} AS foreign_table 
 					ON local_table.{$this->foreignKey} = foreign_table.id
					WHERE local_table.id = {$this->id} LIMIT 1";

		$query4 =  "SELECT local_table.id, pivot_table.*, foreign_table.* FROM {$this->table} AS local_table
					LEFT JOIN {$this->pivotTable} AS pivot_table
					ON pivot_table.{$this->foreignKey} = local_table.id    
					RIGHT JOIN {$this->foreignTable} AS foreign_table 
					ON foreign_table.{$this->localKey}  = pivot_table.id
					WHERE local_table.id = {$this->id}"; //local_table.{$this->foreignKey}  pivot_table.id

		$query5 =  "SELECT * FROM {$this->polimorphicTable} WHERE {$this->polimorphicTable}_type = '{$this->table}' AND {$this->polimorphicTable}_id = '{$this->id}'";

		if($relationship == 'hasOne'){
			return (empty($pivotTable)) ? $query1.'LIMIT 1' : $query2.'LIMIT 1';
		}elseif($relationship == 'hasMany'){
			return (empty($pivotTable)) ? $query1 : $query2;
		}elseif($relationship == 'belongsTo'){
			return (empty($pivotTable)) ? $query3 : $query2;
		}elseif($relationship == 'hasManyThrough'){
			return $query4;
		}elseif($relationship == 'morphMany'){
			return $query5;
		}
	}

//////////////////////////////////////////////////////STANDARD METHODS///////////////////////////////////////////////////////////////

	public function get(){
		if(empty($this->sql)){
			$sql  = "SELECT * FROM {$this->table} WHERE {$this->where}";			
			$this->action($sql);			

		}else{
			$this->action($this->sql);
		}
		return $this;
	}

	public function save(){ 
		$keys = implode($this->dbFields, "`, `");
		$values = "";

		foreach($this->dbFields as $field){
			$values .= "?, ";
			$fields[$field] = $this->$field ? $this->$field : '';
		}
		$sql = "INSERT INTO {$this->table} (`{$keys}`) VALUES (". trim($values, ", ") .")";

		if(!$this->db->query($sql, '', $fields)->error()){
			return true;
		}
	}

	public function create($fields = array()){
		$keys = array();
		$values = '';
		$keyString = '';

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

		if(empty($this->foreignTable) && empty($this->pivotTable) && empty($this->polimorphicTable)){
			$sql = "INSERT INTO {$this->table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
		
		}elseif(!empty($this->foreignTable) && empty($this->pivotTable) && empty($this->polimorphicTable)){
			$sql = "INSERT INTO {$this->foreignTable} (`{$this->foreignKey}`, `" . implode('`, `', $keys) . "`) VALUES ('{$this->id}', {$values})";

		}elseif(!empty($this->foreignTable) && !empty($this->pivotTable) && empty($this->polimorphicTable)){
			$sql = "INSERT INTO {$this->foreignTable} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

		}elseif(!empty($this->polimorphicTable)){
			$sql = "INSERT INTO {$this->polimorphicTable} (`" . implode('`, `', $keys) . "`) VALUES ({$values})"; //work on it $user_id = $auth->user()->id polimorphic_type = $this->table, polimorphic_id = $this->id
		}

		if(!$this->db->query($sql, '', $fields)->error()){
			return true;
		}

		}if(!empty($this->pivotTable)){
			$this->attach($this->db->lastInsertId());
		}
	}

	public function update($fields = array()){
		if($fields == null){
			$set = '';
			foreach($this->dbFields as $field){
				$set .= "`{$field}` = ?, ";
				$fields[$field] = $this->$field;
			}
			$fields['id'] = (int)$this->id;
			$sql = "UPDATE {$this->table} SET ". trim($set, ", ") ." WHERE id = ?";

			if(!$this->db->query($sql, '', $fields)->error()){
				return true;
			}
		}
		
		$set = '';
		$x = 1;

		foreach($fields as $name => $value){
			$set .= "{$name} = ?";
			if($x < count($fields)){
				$set .= ', ';			
			}
			$x++;
		}

		if(empty($this->foreignTable) && empty($this->pivotTable)){
			$sql = "UPDATE {$this->table} SET {$set} WHERE {$this->where}";
			$fields = array_merge($fields, $this->whereValues);

		}elseif(!empty($this->foreignTable) && $this->pivot == false){
			if(!empty($this->data())){	
				$sql = '';	
				foreach ($this->data() as $result) {
					if(!empty($sql)){
						$sql .=	" UNION ";  //check that out
					}
					$sql .= "UPDATE {$this->foreignTable} SET {$set} WHERE id = {$result->id}";
				}
			}
		}elseif($this->pivot == true){
			$auth = new Auth;
			$sql .= "UPDATE {$this->pivotTable} SET {$set} WHERE {$this->foreignKey} = {$auth->user()->id} AND {$this->localKey} = {$this->first()->id}"; //$this->id
		}		

		if(!$this->db->query($sql, '', $fields)->error()){
			return true;
		}
	} //add polimorphic table update capability

	public function delete(){
		if(empty($this->foreignTable) && empty($this->pivotTable)){
			$sql = "DELETE FROM {$this->table} WHERE {$this->where}";	

		}elseif(!empty($this->foreignTable)){
			if(!empty($this->data())){	
				foreach ($this->data() as $result) {
					if(!empty($sql)){
						$sql .=	" UNION ";
					}
					$sql .= "DELETE FROM {$this->foreignTable} WHERE id = {$result->id}";

					if(!empty($this->pivotTable)){
						$this->detach($result->id);	
					}
				}
			}
		}
		(!empty($this->whereValues)) ? $this->whereValues : $params = '';
		if(!$this->db->query($sql, '', $params)->error()){    //correct that 
			return true;
		}
	}

	public function find($user = null){
		if($user){
			$field = (is_numeric($user)) ? 'id' : 'username';
			$this->where(array($field, '=', $user))->get();

			if($this->count()){
				return true;
			}
		}
		return false;
	}

	public function attach($id){
		$sql = "INSERT INTO {$this->pivotTable} ({$this->foreignKey}, {$this->localKey}) VALUES ('{$this->id}', '{$id}')";
		if(!$this->db->query($sql)->error()){
			return true;
		}	
	}         

	public function detach($id){
		$sql = "DELETE FROM {$this->pivotTable} WHERE {$this->foreignKey} = {$this->id} AND {$this->localKey} = {$id}";
		if(!$this->db->query($sql)->error()){
			return true;
		}	
	}

///////////////////////////////////////////////////RELATIONSHIP METHODS//////////////////////////////////////////////////////////////

	public function hasOne($foreignTable, $pivotTable = null, $foreignKey = null, $localKey = null){
		$this->sql = $this->relationalshipQueryBuilder('hasOne', $foreignTable, $pivotTable, $foreignKey, $localKey);
		return $this;
	}

	public function hasMany($foreignTable, $pivotTable = null, $foreignKey = null, $localKey = null){
		$this->sql = $this->relationalshipQueryBuilder('hasMany', $foreignTable, $pivotTable, $foreignKey, $localKey);
		return $this;
	}

	public function belongsTo($foreignTable, $pivotTable = null, $foreignKey = null, $localKey = null){
		$this->sql = $this->relationalshipQueryBuilder('belongsTo', $foreignTable, $pivotTable, $foreignKey, $localKey);
		return $this;
	}

	public function hasManyThrough($foreignTable, $pivotTable, $foreignKey, $localKey){
		$this->sql = $this->relationalshipQueryBuilder('hasManyThrough', $foreignTable, $pivotTable, $foreignKey, $localKey);
		return $this;
	}

	public function morphMany($polimorphicTable){
		$this->sql = $this->relationalshipQueryBuilder('morphMany', '', '', '', '', $polimorphicTable);
		return $this;    
    }

//////////////////////////////////////////////////////RETURN DATA METHODS////////////////////////////////////////////////////////////

	public function data(){
		$results = array_merge($this->results, $this->db->results());
		$this->results = [];
		return $results;
	}

	public function first(){
		return $this->data()[0];
	}

	public function count(){
		return count($this->data());
	}
}