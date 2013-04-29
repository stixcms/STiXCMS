<?
/////////////////////////////
//                         //
//     module Objects      //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class ModuleRecord {
	        var $mod_id;
	        var $URI;
	        var $title;
	        var $modorder;
	        var $place;
	        var $locale;
	        var $cat_id;
	        var $status;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	        var $OwnerUserID;
	        var $OwnerGroupID;
	        var $RowPerms;
	
	        function fillRecord($array) {
	                $this->mod_id=$array[0];
	                $this->URI=$array[1];
	                $this->title=$array[2];
	                $this->modorder=$array[3];
	                $this->place=$array[4];
	                $this->status=$array[5];
	                $this->createdate=$array[6];
	                $this->createdby=$array[7];
	                $this->lastupdated=$array[8];
	                $this->lastupdatedby=$array[9];
	                $this->OwnerUserID=$array[10];
	                $this->OwnerGroupID=$array[11];
	                $this->RowPerms=$array[12];
	                $this->locale=$array[13];
	                $this->cat_id=$array[14];
	        }
	}
	
	Class ModuleDBRecord extends ModuleRecord {
	        var $dbconn;
	
	        function ModuleDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT mod_id,URI,title,modorder,place,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,locale,cat_id FROM module WHERE mod_id=".$this->mod_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO module (URI,title,modorder,place,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,locale,cat_id) VALUES ('".$this->URI."','".$this->title."',".$this->modorder.",'".$this->place."','".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",'".$this->RowPerms."',".$this->locale.",".$this->cat_id.")";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->mod_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE module SET URI='".$this->URI."',title='".$this->title."',modorder=".$this->modorder.",place='".$this->place."',status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.",OwnerUserID=".$this->OwnerUserID.",OwnerGroupID=".$this->OwnerGroupID.",RowPerms='".$this->RowPerms."',locale=".$this->locale.",cat_id=".$this->cat_id." WHERE mod_id=".$this->mod_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	        	global $IN_MODULE_DIR;
	        	$this->get();	        	
	                $sql="DELETE FROM module WHERE mod_id=".$this->mod_id;
	                $success=$this->dbconn->query($sql);
			if($success) if($this->URI!="") @unlink($IN_MODULE_DIR.$this->URI);	                	                
	                return $success;
	        }
	        

	}
	
	Class ModuleList {
	        var $list;
	        var $of_node;
	        var $by_locale;
		var $by_status;
		var $sort_order;

		function ModuleList() {
			$this->sort_order="modorder";
		}
							
	        function populate($dbconn,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT mod_id,URI,title,modorder,place,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,locale,cat_id FROM module WHERE mod_id>0 ";
	                if($this->of_node!="") $sql.=" AND cat_id=".$this->of_node;
			if($this->by_status!="") $sql.=" AND status='".$this->by_status."'";
			if($this->by_locale!="") $sql.=" AND locale=".$this->by_locale;
			if($uid!=1) $sql.=" AND ( (OwnerUserID=".$uid." AND (MID(RowPerms,1,1) AND 1) ) OR (OwnerGroupID=".$gid." AND (MID(RowPerms,2,1) AND 1) ) OR (MID(RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY ".$this->sort_order;
				
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new ModuleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }
	
	}
?>