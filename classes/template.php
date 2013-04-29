<?
/////////////////////////////
//                         //
//    template Objects     //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class TemplateRecord {
	        var $temp_id;
	        var $title;
	        var $html;
	        var $status;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	        var $OwnerUserID;
	        var $OwnerGroupID;
	        var $RowPerms;
	
	        function fillRecord($array) {
	                $this->temp_id=$array[0];
	                $this->title=$array[1];
	                $this->html=$array[2];
	                $this->status=$array[3];
	                $this->createdate=$array[4];
	                $this->createdby=$array[5];
	                $this->lastupdated=$array[6];
	                $this->lastupdatedby=$array[7];
	                $this->OwnerUserID=$array[8];
	                $this->OwnerGroupID=$array[9];
	                $this->RowPerms=$array[10];
	        }
	}
	
	Class TemplateDBRecord extends TemplateRecord {
	        var $dbconn;
	
	        function TemplateDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT temp_id,title,html,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms FROM template WHERE temp_id=".$this->temp_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO template (title,html,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms) VALUES ('".$this->title."','".$this->html."','".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",".$this->RowPerms.")";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->temp_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE template SET title='".$this->title."',html='".$this->html."',status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.",OwnerUserID=".$this->OwnerUserID.",OwnerGroupID=".$this->OwnerGroupID.",RowPerms=".$this->RowPerms." WHERE temp_id=".$this->temp_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM template WHERE temp_id=".$this->temp_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
      
	}
	
	Class TemplateList {
	        var $list;
		var $by_status;
		var $status_comp;
		var $full;
				
		function TemplateList() {
			$this->by_status="";
			$this->status_comp="=";
			$this->full=false;
		}
			        
		function populate($dbconn,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	                			
			if($this->full) $sql="SELECT a.temp_id,a.title,a.html,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			else $sql="SELECT a.temp_id,a.title,'',a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM template a WHERE a.temp_id>0";
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";			
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			$sql.=" ORDER BY a.title";

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new TemplateRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
		}	
	}
?>