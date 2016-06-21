<?php

class Database{
	private $prod_db_host = "OR-TSSQL01.response.corp";
	private $prod_db_user = "dwWEIci16rGByGw8";
	private $prod_db_pass= "R22tCY2kkK8UguFU";
	private $dev_db_host = "AV-RMGDEVDB01.blooms.corp";
	private $dev_db_user = "8v8UzAEARNFIVNo2";
	private $dev_db_pass = "NzdYc5oh4XeDXecY"; 
	
	public $link = null;
	public $dbaSelected = null;


	public function _construct(){
	}
	public function _destruct(){
	}

	public function getInstance($connection = 'development', $dba = 'DBA'){
		switch($connection){
			case 'development':
				$this->link = mssql_connect($this->dev_db_host, $this->dev_db_user, $this->dev_db_pass) or die("could not connect to sql server");
				$this->dbaSelected = mssql_select_db($dba, $this->link) or die("could not connect to database");
				break;
			case 'production':
				$this->link = mssql_connect($this->prod_db_host, $this->prod_db_user, $this->prod_db_pass) or die("could not connect to sql server");
                                $this->dbaSelected = mssql_select_db($dba, $this->link) or die("could not connect to database");
				break;
		}
	}
	public function closeInstance(){
		mssql_close($this->link);
	}
	public function fetchAll($sql){
		$assocArr = array();
		$query = mssql_query($sql);
		while ($row = mssql_fetch_assoc($query)) {
			array_push($assocArr, $row);
		        //echo '<li>' . $row['name'] . ' (' . $row['username'] . ')</li>';
		}
		return $assocArr;
	}
}

?>
