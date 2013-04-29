<?
/////////////////////////////
//                         //
//    usergroup Objects     //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class UsergroupRecord {
	        var $group_id;
	        var $name;
	        var $perms;
	        var $startcat;
	
	        function fillRecord($array) {
	                $this->group_id=$array[0];
	                $this->name=$array[1];
	                $this->perms=$array[2];
	                $this->startcat=$array[3];
	        }
	}
	
	Class UsergroupDBRecord extends UsergroupRecord {
	        var $dbconn;
	
	        function UsergroupDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT group_id,name,perms,startcat FROM usergroup WHERE group_id=".$this->group_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO usergroup (group_id,name,perms,startcat) VALUES (".$this->group_id.",'".$this->name."','".$this->perms."',".$this->startcat.")";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->group_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE usergroup SET name='".$this->name."',perms='".$this->perms."',startcat=".$this->startcat." WHERE group_id=".$this->group_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	        	$found=0;
	        	$sql="SELECT COUNT(*) FROM users WHERE group_id=".$this->group_id;
	        	if($this->dbconn->query($sql)) while($this->dbconn->next_row()) $found=$this->dbconn->row[0];
	        	if($found>0) return false;
	                $sql="DELETE FROM usergroup WHERE group_id=".$this->group_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	}
	
	Class UsergroupList {
	        var $list;
	
	        function populate($dbconn) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT group_id,name,perms,startcat FROM usergroup ORDER BY name";
	
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new UsergroupRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }

		function getname($id) {
			$counter=0;
			while($counter<count($this->list)) {
				if($id==$this->list[$counter]->group_id) return $this->list[$counter]->name;
				$counter++;
			}
			return "";
		}	
	}
?>