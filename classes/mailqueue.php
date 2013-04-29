<?
/////////////////////////////
//                         //
//    mailqueue Objects    //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class mailqueueRecord {
	        var $row_id;
	        var $mailer_id;
	        var $email;
	        var $issent;
	
	        function fillRecord($array) {
	                $this->row_id=$array[0];
	                $this->mailer_id=$array[1];
	                $this->email=$array[2];
	                $this->issent=$array[3];
	        }
	}
	
	Class mailqueueDBRecord extends mailqueueRecord {
	        var $dbconn;
	
	        function mailqueueDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT row_id,mailer_id,email,issent FROM mailqueue WHERE row_id=".$this->row_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO mailqueue (mailer_id,email,issent) VALUES (".$this->mailer_id.",'".$this->email."','".$this->issent."')";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->row_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE mailqueue SET mailer_id=".$this->mailer_id.",email='".$this->email."',issent='".$this->issent."' WHERE row_id=".$this->row_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM mailqueue WHERE row_id=".$this->row_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	}
	
	Class mailqueueList {
	        var $list;
	
	        function populate($dbconn) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT row_id,mailer_id,email,issent FROM mailqueue";
	
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new mailqueueRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }

	        function UnsentOfMailer($dbconn,$mid,$lim="") {
	                $counter=0;
	                $this->list=null;
			if($lim!="") $end=intval($lim); else $end=999999999;
			
	                $sql="SELECT row_id,mailer_id,email,issent FROM mailqueue WHERE mailer_id=".$mid." AND issent='N'";
	
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row() && $counter<$end) {
	                                $this->list[$counter]=new mailqueueRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }
	}
?>