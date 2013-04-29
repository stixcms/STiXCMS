<?
/////////////////////////////
//                         //
//       ads Objects       //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class AdsRecord {
	        var $ad_id;
	        var $name;
	        var $att_id;
	        var $adtext;
	        var $hlink;
	        var $aorder;
	        var $status;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	        var $OwnerUserID;
	        var $OwnerGroupID;
	        var $RowPerms;
	
	        function fillRecord($array) {
	                $this->ad_id=$array[0];
	                $this->name=$array[1];
	                $this->att_id=$array[2];
	                $this->adtext=$array[3];
	                $this->hlink=$array[4];
	                $this->aorder=$array[5];
	                $this->status=$array[6];
	                $this->createdate=$array[7];
	                $this->createdby=$array[8];
	                $this->lastupdated=$array[9];
	                $this->lastupdatedby=$array[10];
	                $this->OwnerUserID=$array[11];
	                $this->OwnerGroupID=$array[12];
	                $this->RowPerms=$array[13];
	        }
	}
	
	Class AdsDBRecord extends AdsRecord {
	        var $dbconn;
	
	        function AdsDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT ad_id,name,att_id,adtext,hlink,aorder,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms FROM ads WHERE ad_id=".$this->ad_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO ads (name,att_id,adtext,hlink,aorder,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms) VALUES ('".$this->name."',".$this->att_id.",'".$this->adtext."','".$this->hlink."',".$this->aorder.",'".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",".$this->RowPerms.")";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->ad_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE ads SET name='".$this->name."',att_id=".$this->att_id.",adtext='".$this->adtext."',hlink='".$this->hlink."',aorder=".$this->aorder.",status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.",OwnerUserID=".$this->OwnerUserID.",OwnerGroupID=".$this->OwnerGroupID.",RowPerms=".$this->RowPerms." WHERE ad_id=".$this->ad_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM ads WHERE ad_id=".$this->ad_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	        
		function attach2node($id) {
			$sql="INSERT INTO ads_cat (cat_id,ad_id) VALUES (".$id.", ".$this->ad_id.")";
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}	        

	        function detachAll() {
	                $sql="DELETE FROM ads_cat WHERE ad_id=".$this->ad_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	        		
		function getCatArray() {
			$ret=Array();
			$counter=0;
			$sql="SELECT cat_id FROM ads_cat WHERE ad_id=".$this->ad_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $ret[$counter++]=$this->dbconn->row[0];
			return $ret;
		}
	}
	
	Class AdsList {
	        var $list;
		var $by_status;
		var $status_comp;
		var $sort_order;
			
		function AdsList() {
			$this->by_status="";
			$this->status_comp="=";
			$this->sort_order="a.aorder";
		}
			        
		function getAdsOfNode($dbconn,$id,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	                			
			$sql="SELECT a.ad_id,a.name,a.att_id,a.adtext,a.hlink,a.aorder,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM ads a, ads_cat b WHERE a.ad_id=b.ad_id AND b.cat_id=".$id;
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";			
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			$sql.=" ORDER BY ".$this->sort_order;

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new AdsRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
		}	
	}
?>