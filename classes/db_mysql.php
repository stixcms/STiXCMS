<?
/////////////////////////////
//                         //
//    Database Objects     //
//                         //
/////////////////////////////

	$SQL_ISNULL="IFNULL";

	Class stcmt_db {
		var $conn;
		var $res;
		var $row;
		var $connected;
		

		function stcmt_db() {	// constructor
			$this->conn=null;
			$this->res=null;
			$this->row=null;
			$this->connected=false;	
		}
		
		function open($dbserver,$dbuser,$dbpass,$dbname) {	// open a connection
			$this->conn=@mysql_connect($dbserver,$dbuser,$dbpass);
			if(mysql_errno()==0) {
				if(mysql_select_db($dbname,$this->conn)) $this->connected=true;
			}
		}
		
		function openp($dbserver,$dbuser,$dbpass,$dbname) {	// open a persistent connection
			$this->conn=@mysql_pconnect($dbserver,$dbuser,$dbpass);
			if(mysql_errno()==0) {
				if(mysql_select_db($dbname,$this->conn)) $this->connected=true;
			}
		}
		
		function close() {	// close connection
			if($this->connected) {
				if($this->res) mysql_free_result($this->res);
				@mysql_close($this->conn);
				$this->connected=false;
			}
		}
		
		function query($sql) {	// send an sql query statement
			if($this->connected) {
				if($this->res) @mysql_free_result($this->res);
				$this->res=@mysql_query($sql,$this->conn);
				if($this->res) return true;
			}
			return false;
		}
		
		function insert_id() {	// return last inserted id
			return @mysql_insert_id($this->conn);	
		}

		function affected_rows() {	// return affected rows by INSERT,DELETE or UPDATE
			return mysql_affected_rows($this->conn);	
		}
				
		function next_row() {	// get next row (true) or nothing (false)
			if($this->connected && $this->res) {
				$this->row=@mysql_fetch_row($this->res);
				if($this->row) return true;
			}
			if($this->res) { @mysql_free_result($this->res); $this->res=null; $this->row=null; }
			return false;
		}
	}
?>