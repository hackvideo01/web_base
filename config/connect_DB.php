<?php

require_once('conf_db.php');

class MySQLDatabase {
	
	/**
	 *　接続リソース
	 */
	protected $_connect = null;
	
	/**
	 * デストラクタ
	 **/
	public function __destruct() {
		$this->db_close();
	}
	
	/**
	 * コンストラクタ
	 **/
	public function __construct() {
	}

	/**
	 *　データベース接続
	 */
	public function db_connect() {
		
		//接続済みの場合は戻す
		if($this->isConnected()) {
			return;
		}
		
		$dsn = "mysql:dbname=".DB_NAME.";host=".DB_HOST.";charset=".DB_CHARSET;
		$option = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
			PDO::ATTR_EMULATE_PREPARES => false,
			PDO::ATTR_STRINGIFY_FETCHES => false,
			PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
		);

		echo "Connected to DATABASE";
		
		try {
			$this->_connect = new PDO($dsn, DB_USER, DB_PASS, $option);
		} catch (PDOException $e){
			echo $e->getMessage();
		}
	}
	
	/**
	 *　接続チェック
	 */
	public function isConnected() {
		return ((bool) ($this->_connect instanceof PDO));
	}
	
	/**
	 *　接続クローズ
	 */
	public function db_close() {
		$this->_connect = null;
	}
	
	/**
	 * SQL実行
	 **/
	function execute($sql,$param = null){
		$this->db_connect();
		$stmt = $this->_connect->prepare($sql);
		while ($current = current($param)) {
			$stmt->bindParam(key($array)+1, $current[0], $current[1]);
		}
		$stmt->execute();
		$this->db_close();
		return $stmt;
	}

	/**
	 * SQL実行結果 → 連想配列にて取得
	 **/
	function get_all($sql,$param = null){
		$stmt = $this->execute( $sql, $param );
		while ($fetchArr = $stmt->fetch()) {
			$retArray[] = $fetchArr;
		}
		return $retArray;
	}
	
	/**
	 * SQL実行結果 → 単一行を配列にて取得
	 **/
	function get_one( $sql, $param = null ){
		$stmt = $this->execute( $sql, $param );
		return $stmt->fetch();
	}

	/**
	 * SQL実行結果 → 件数取得
	 **/
	function get_rowcount( $sql,$param = null ){
		$stmt = $this->execute( $sql, $param );
		$count=$stmt->rowCount();
		return $count;
	}
	function execute_getID($sql,$param = null){
		$this->db_connect();
		$stmt = $this->_connect->prepare($sql);
		while ($current = current($param)) {

			$stmt->bindParam(key($array)+1, $current[0], $current[1]);
		}
		$stmt->execute();
		$lastInsertId = $this->_connect->lastInsertId();
		$this->db_close();
		return $lastInsertId;
	}
	
}

?>
