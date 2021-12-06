<?php 
include_once './config/define.php'; 
class Database{
	protected $connect;
	protected $table;
	//CONNECT DATABASE
	public function __construct($params = null){
		if($params == null){
			$params['sever']    = DB_HOST;
			$params['username']	= DB_USER;
			$params['password']	= DB_PASS;
			$params['database']	= DB_NAME;
		}

		$options = array(
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		try {
			if(DATABASE_TYPE == 'mysql'){
				$this->connect = new PDO('mysql:host='.$params['sever'].';dbname='.$params['database'], $params['username'], $params['password'], $options);
			}
			if(DATABASE_TYPE == 'sqlite'){
				$sqliteFile = PATH_DATABASE.'/'.$params['database'].'.db'; // sqlite3
				$this->connect = new PDO('sqlite:'.$sqliteFile , null, null, $options);
			}
		}catch (PDOException $e) {
			echo $e->getMessage();
			exit();
		}

	}

	// DISCONNECT DATABASE
	public function __destruct(){
		$this->connect = null;
	}

	// SET CONNECT
	public function setConnect($connect){
		$this->connect = $connect;
	}

	// GET CONNECT
	public function getConnect(){
		return $this->connect;
	}
	
	// SET TABLE
	public function setTable($table){
		$this->table = $table;
	}

	// GET TABLE
	public function getTable(){
		return $this->table;
	}

	// EXECUTE QUERY
	public function execute($sql)	{
		$stmt = $this->connect->prepare($sql);
		$stmt->execute();
		return $stmt;
	}

	// SINGLE RECORD
	public function fetchRow($query){
		$result = array();
		if(!empty($query)){
			$stmt = $this->connect->prepare($query);
			$stmt->execute();
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		return $result;
	}

	// FETCH ALL
	public function fetchAll($query){
		$result = array();
		if(!empty($query)){
			$stmt = $this->connect->prepare($query);
			$stmt->execute();
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		return $result;
	}

	// CREATE WHERE DELTE SQL
	public function createWhereDeleteSQL($data){
		$newWhere = '';
		if(!empty($data)){
			foreach($data as $id) {
				$newWhere .= "'" . $id . "', ";
			}
			$newWhere .= "'0'";
		}
		return $newWhere;
	}

	// DELETE
	public function delete($where){
		$newWhere 	= $this->createWhereDeleteSQL($where);
		$query 		= "DELETE FROM `$this->table` WHERE `id` IN ($newWhere)";
		$stmt = $this->execute($query);
		return $stmt;
	}

	public function lastID(){
		return $this->connect->lastInsertId();
	}

	// CREATE INSERT SQL
	public function createInsertSQL($data){
		$newQuery = array();
		$cols = '';
		$vals = '';
		if(!empty($data)){
			foreach($data as $key=> $value){
				$cols .= ", `$key`";
				$vals .= ", '$value'";
			}
		}
		$newQuery['cols'] = substr($cols, 2);
		$newQuery['vals'] = substr($vals, 2);
		return $newQuery;
	}

	public function insert($data, $type = 'single'){
		if($type == 'single'){
			$newQuery 	= $this->createInsertSQL($data);
			$query 		= "INSERT INTO `$this->table`(".$newQuery['cols'].") VALUES (".$newQuery['vals'].")";
			$this->execute($query);
		}else{
			foreach($data as $value){
				$newQuery = $this->createInsertSQL($value);
				$query = "INSERT INTO `$this->table`(".$newQuery['cols'].") VALUES (".$newQuery['vals'].")";
				$this->execute($query);
			}
		}
		return $this->lastID();
	}

	// UPDATE
	public function update($data, $where){
		$newSet 	= $this->createUpdateSQL($data);
		 $newWhere 	= $this->createWhereUpdateSQL($where); 
		$query = "UPDATE `$this->table` SET " . $newSet . " WHERE $newWhere";
		$this->execute($query);
	}
	
	// CREATE UPDATE SQL
	public function createUpdateSQL($data){
		$newQuery = "";
		if(!empty($data)){
			foreach($data as $key => $value){
				$newQuery .= ", `$key` = '$value'";
			}
		}
		$newQuery = substr($newQuery, 2);
		return $newQuery;
	}

	// CREATE WHERE UPDATE SQL
	public function createWhereUpdateSQL($data){
		$newWhere = '';
		if(!empty($data)){
			foreach($data as $value){
				$newWhere[] = "`$value[0]` = '$value[1]'";
				@$newWhere[] = $value[2];
			}
			$newWhere = implode(" ", $newWhere);
		}
		return $newWhere;
	}
}	
?>