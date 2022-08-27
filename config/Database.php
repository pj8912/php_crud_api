<?php

class Database{
	private $host = "localhost";
	private $dbname = "crud_api";
	private $uname = "root";
	private $pwd  = "";


	public function connect(){
		try{
			$conn = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname, $this->uname, $this->pwd);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return $conn;
		}
		catch(PDOException $e){
			echo "DB_CONN_ERR: ".$e->getMessage();
			exit();
		}

	}

}


