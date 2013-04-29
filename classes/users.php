<?
/////////////////////////////
//                         //
//	Users Objects      //
//                         //
/////////////////////////////

	Class UserRecord {
		var $user_id;
		var $group_id;
		var $username;
  		var $password;
		var $name;
		var $email;
  		var $status;
	        var $maxartstatus;
	          		
  		function fillRecord($array) {
			$this->user_id=$array[0];
			$this->group_id=$array[1];
			$this->username=$array[2];
			$this->password=$array[3];
			$this->name=$array[4];
			$this->email=$array[5];
			$this->status=$array[6];
	                $this->maxartstatus=$array[7];			
  		}
	}  		
  	
  	Class UserDBRecord extends UserRecord {	
  		var $dbconn;
  		
  		function UserDBRecord($dbc) {
  			$this->dbconn=$dbc;	
  		}
  		
		function get($id) {
			$sql="SELECT user_id,group_id,username,password,name,email,status,maxartstatus FROM users WHERE user_id=".$id;
			if($this->dbconn->query($sql)) {
				while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
			}	
		}

		function auth($u,$p) {
			$this->user_id=0;
			$sql="SELECT user_id,group_id,username,password,name,email,status,maxartstatus FROM users WHERE username='".$u."' AND password='".$p."' AND status='A'";
			if($this->dbconn->query($sql)) {
				while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
			}
			return ($this->user_id>0)?true:false;
		}
		
		function insert() {
			$sql="INSERT INTO users (group_id,username,password,name,email,status,maxartstatus) VALUES (".$this->group_id.",'".$this->username."','".$this->password."','".$this->name."','".$this->email."','".$this->status."','".$this->maxartstatus."')";
			$succ=$this->dbconn->query($sql);
			$this->user_id=$this->dbconn->insert_id();
			if(!$succ) return false; else return true;
		}
		
		function update() {
			$sql="UPDATE users SET group_id=".$this->group_id.", username='".$this->username."', password='".$this->password."', name='".$this->name."', email='".$this->email."', status='".$this->status."',maxartstatus='".$this->maxartstatus."' WHERE user_id=".$this->user_id;
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}
		
		function delete() {
			$sql="DELETE FROM users WHERE user_id=".$this->user_id;
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}		
	}

	
	Class UsernameList {
		var $users;
  		var $dbconn;
  		
		function populate($dbc) {
			$counter=0;
			$this->users=null;
			
			$sql="SELECT user_id,group_id,username,password,name,email,status,maxartstatus FROM users";
			$sql.=" ORDER BY username";
			if($dbc->query($sql)) {
				while($dbc->next_row()) {
					$this->users[$counter]=new UserRecord();
					$this->users[$counter]->fillRecord($dbc->row);
					$counter++;
				}
			}
		}
		
		function which($id) {
			$counter=0;
			while($counter<count($this->users)) {
				if($id==$this->users[$counter]->user_id) return $this->users[$counter]->username;
				$counter++;
			}
			return "";
		}
	}

?>