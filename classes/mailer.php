<?
/////////////////////////////
//                         //
//     mailer Objects      //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class MailerRecord {
	        var $mailer_id;
	        var $fromaddr;
	        var $subject;
	        var $body;
	        var $lastsent;
	        var $lastsentcount;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	        var $OwnerUserID;
	        var $OwnerGroupID;
	        var $RowPerms;
	
	        function fillRecord($array) {
	                $this->mailer_id=$array[0];
	                $this->fromaddr=$array[1];
	                $this->subject=$array[2];
	                $this->body=$array[3];
	                $this->lastsent=$array[4];
	                $this->lastsentcount=$array[5];
	                $this->createdate=$array[6];
	                $this->createdby=$array[7];
	                $this->lastupdated=$array[8];
	                $this->lastupdatedby=$array[9];
	                $this->OwnerUserID=$array[10];
	                $this->OwnerGroupID=$array[11];
	                $this->RowPerms=$array[12];
	        }
	}
	
	Class MailerDBRecord extends MailerRecord {
	        var $dbconn;
	
	        function MailerDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT mailer_id,fromaddr,subject,body,lastsent,lastsentcount,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms FROM mailer WHERE mailer_id=".$this->mailer_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO mailer (fromaddr,subject,body,lastsent,lastsentcount,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms) VALUES ('".$this->fromaddr."','".$this->subject."','".$this->body."',".$this->lastsent.",".$this->lastsentcount.",".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",'".$this->RowPerms."')";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->mailer_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE mailer SET fromaddr='".$this->fromaddr."', subject='".$this->subject."',body='".$this->body."',lastsent=".$this->lastsent.",lastsentcount=".$this->lastsentcount.",createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.",OwnerUserID=".$this->OwnerUserID.",OwnerGroupID=".$this->OwnerGroupID.",RowPerms='".$this->RowPerms."' WHERE mailer_id=".$this->mailer_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM mailer WHERE mailer_id=".$this->mailer_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	}
	
	Class MailerList {
	        var $list;
	
	        function populate($dbconn,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT mailer_id,fromaddr,subject,body,lastsent,lastsentcount,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms FROM mailer WHERE mailer_id>0";
			if($uid!=1) $sql.=" AND ( (OwnerUserID=".$uid." AND (MID(RowPerms,1,1) AND 1) ) OR (OwnerGroupID=".$gid." AND (MID(RowPerms,2,1) AND 1) ) OR (MID(RowPerms,3,1) AND 1) )";
	                
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new MailerRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }
	
	}
?>