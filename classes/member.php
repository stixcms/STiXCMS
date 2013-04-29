<?
/////////////////////////////
//                         //
//     member Objects      //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class MemberRecord {
	        var $member_id;
	        var $date_created;
	        var $date_updated;
	        var $name;
	        var $lastname;
	        var $username;
	        var $password;
	        var $address;
	        var $zip;
	        var $city;
	        var $country;
	        var $phone;
	        var $fax;
	        var $email;
	        var $company;
	        var $afm;
	        var $doy;
	        var $mailinglist;
	        var $status;
	
	        function fillRecord($array) {
	                $this->member_id=$array[0];
	                $this->date_created=$array[1];
	                $this->date_updated=$array[2];
	                $this->name=$array[3];
	                $this->lastname=$array[4];
	                $this->username=$array[5];
	                $this->password=$array[6];
	                $this->address=$array[7];
	                $this->zip=$array[8];
	                $this->city=$array[9];
	                $this->country=$array[10];
	                $this->phone=$array[11];
	                $this->fax=$array[12];
	                $this->email=$array[13];
	                $this->company=$array[14];
	                $this->afm=$array[15];
	                $this->doy=$array[16];
	                $this->mailinglist=$array[17];
	                $this->status=$array[18];
	        }
	}
	
	Class MemberDBRecord extends MemberRecord {
	        var $dbconn;
	
	        function MemberDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT member_id,date_created,date_updated,name,lastname,username,password,address,zip,city,country,phone,fax,email,company,afm,doy,mailinglist,status FROM member WHERE member_id=".$this->member_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }

	        function auth($u,$p) {
	                $sql="SELECT member_id,date_created,date_updated,name,lastname,username,password,address,zip,city,country,phone,fax,email,company,afm,doy,mailinglist,status FROM member WHERE username='".$u."' AND password='".$p."'";
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	        	
	        function insert() {
	                $sql="INSERT INTO member (date_created,date_updated,name,lastname,username,password,address,zip,city,country,phone,fax,email,company,afm,doy,mailinglist,status) VALUES (".$this->date_created.",".$this->date_updated.",'".$this->name."','".$this->lastname."','".$this->username."','".$this->password."','".$this->address."','".$this->zip."','".$this->city."','".$this->country."','".$this->phone."','".$this->fax."','".$this->email."','".$this->company."','".$this->afm."','".$this->doy."','".$this->mailinglist."','".$this->status."')";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->member_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE member SET date_created=".$this->date_created.",date_updated=".$this->date_updated.",name='".$this->name."',lastname='".$this->lastname."',username='".$this->username."',password='".$this->password."',address='".$this->address."',zip='".$this->zip."',city='".$this->city."',country='".$this->country."',phone='".$this->phone."',fax='".$this->fax."',email='".$this->email."',company='".$this->company."',afm='".$this->afm."',doy='".$this->doy."',mailinglist='".$this->mailinglist."',status='".$this->status."' WHERE member_id=".$this->member_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM member WHERE member_id=".$this->member_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	}
	
	Class MemberList {
	        var $list;
		var $by_status;
		var $only_4mail;
		
	        function populate($dbconn) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT member_id,date_created,date_updated,name,lastname,username,password,address,zip,city,country,phone,fax,email,company,afm,doy,mailinglist,status FROM member WHERE member_id>0";
	                if($by_status!="") $sql.=" AND status='".$by_status."'";
	                if($only_4mail!="") $sql.=" AND mailinglist='".$only_4mail."'";
	                $sql.=" ORDER BY date_created DESC";
	
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new MemberRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }
	
	}
?>