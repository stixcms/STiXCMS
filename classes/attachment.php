<?
	/////////////////////////////
	//                         //
	//    attachment Objects     //
	//                         //
	/////////////////////////////
	// Code by Spiros Trivizas //
	//       stsoft@mailbox.gr //
	/////////////////////////////
	
	Class AttachmentRecord {
	        var $att_id;
	        var $mime;
	        var $URI;
	        var $title;
	        var $width;
	        var $height;
	        var $filesize;
	        var $attorder;
	        var $status;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	        var $OwnerUserID;
	        var $OwnerGroupID;
	        var $RowPerms;
	        var $typ;
	        	
	        function fillRecord($array) {
	                $this->att_id=$array[0];
	                $this->mime=$array[1];
	                $this->URI=$array[2];
	                $this->title=$array[3];
	                $this->width=$array[4];
	                $this->height=$array[5];
	                $this->filesize=$array[6];
	                $this->attorder=$array[7];
	                $this->status=$array[8];
	                $this->createdate=$array[9];
	                $this->createdby=$array[10];
	                $this->lastupdated=$array[11];
	                $this->lastupdatedby=$array[12];
	  		$this->OwnerUserID=$array[13];
	  		$this->OwnerGroupID=$array[14];
	  		$this->RowPerms=$array[15];			                
	  		$this->typ=$array[16];
	        }
	}
	
	Class AttachmentDBRecord extends AttachmentRecord {
	        var $dbconn;
	
	        function AttachmentDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT att_id,mime,URI,title,width,height,filesize,attorder,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,typ FROM attachment WHERE att_id=".$this->att_id;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO attachment (mime,URI,title,width,height,filesize,attorder,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,typ) VALUES ('".$this->mime."','".$this->URI."','".$this->title."',".$this->width.",".$this->height.",".$this->filesize.",".$this->attorder.",'".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",'".$this->RowPerms."',".$this->typ.")";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->att_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE attachment SET mime='".$this->mime."',URI='".$this->URI."',title='".$this->title."',width=".$this->width.",height=".$this->height.",filesize=".$this->filesize.",attorder=".$this->attorder.",status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.", OwnerUserID=".$this->OwnerUserID.", OwnerGroupID=".$this->OwnerGroupID.", RowPerms='".$this->RowPerms."', typ=".$this->typ." WHERE att_id=".$this->att_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM attachment WHERE att_id=".$this->att_id;
	                $success=$this->dbconn->query($sql);
	                if($success) {
		                $sql="DELETE FROM att_cat WHERE att_id=".$this->att_id;
		                $success=$this->dbconn->query($sql);	                	
	                }
	                //@todo: delete from disk too
	                return $success;
	        }
	        
	        function getTitle($id)
	        {
	        	$retval="";
	        $sql="SELECT title FROM attachment WHERE att_id=".$id;
	       
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $retval=$this->dbconn->row[0];
	                        return $retval;
	        }
	        
	         function getURI($id)
	        {
	        	$retval="";
	        $sql="SELECT URI FROM attachment WHERE att_id=".$id;
	       
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $retval=$this->dbconn->row[0];
	                        return $retval;
	        }
	        
		function attach2node($id) {
			$sql="INSERT INTO att_cat (att_id,cat_id) VALUES (".$this->att_id.",".$id.")";
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}	        
	}
	
	Class AttachmentList {
	        var $list;
		var $by_status;
		 
		
	        function populate($dbconn,$typ,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT att_id,mime,URI,title,width,height,filesize,attorder,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,typ FROM attachment WHERE att_id>0";
			if($this->by_status!="") $sql.=" AND status='".$this->by_status."'";
			if($typ>0) $sql.=" AND typ=".$typ;
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";			
			$sql.=" ORDER BY title";

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new AttachmentRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }

		function getAttOfNode($dbconn,$id,$typ,$uid,$gid) {
			$counter=0;
			$this->list=null;
	
			$sql="SELECT a.att_id,a.mime,a.URI,a.title,a.width,a.height,a.filesize,a.attorder,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms,a.typ FROM attachment a, att_cat b WHERE a.att_id=b.att_id AND b.cat_id=".$id;
			if($this->by_status!="") $sql.=" AND a.status='".$this->by_status."'";
			if($typ>0) $sql.=" AND a.typ=".$typ;
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY a.attorder";
			
			if($dbconn->query($sql)) {
				while($dbconn->next_row()) {
					$this->list[$counter]=new AttachmentRecord();
					$this->list[$counter]->fillRecord($dbconn->row);
					$counter++;
				}
			}
		}	

		function getRelatedAtt($dbconn,$id,$typ,$uid,$gid) {
			$counter=0;
			$this->list=null;
	
			$sql="SELECT a.att_id,a.mime,a.URI,a.title,a.width,a.height,a.filesize,a.attorder,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms,a.typ FROM attachment a, related b WHERE a.att_id=b.rel_id AND b.pri_id=".$id;
			if($this->by_status!="") $sql.=" AND a.status='".$this->by_status."'";
			if($typ>0) $sql.=" AND a.typ=".$typ." AND b.rel_type=".$typ;
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY a.attorder";
			
			if($dbconn->query($sql)) {
				while($dbconn->next_row()) {
					$this->list[$counter]=new AttachmentRecord();
					$this->list[$counter]->fillRecord($dbconn->row);
					$counter++;
				}
			}
		}
		
		function getRelatedGalAtt($dbconn,$id,$typ,$uid,$gid) {
			$counter=0;
			$this->list=null;
	
			$sql="SELECT a.att_id,a.mime,a.URI,a.title,a.width,a.height,a.filesize,a.attorder,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms,a.typ FROM attachment a, gallery_att b WHERE a.att_id=b.att_id1 AND b.gal_id=".$id;
			if($this->by_status!="") $sql.=" AND a.status='".$this->by_status."'";
			if($typ>0) $sql.=" AND a.typ=".$typ;//." AND b.rel_type=".$typ;
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY b.attorder";
				
			if($dbconn->query($sql)) {
				while($dbconn->next_row()) {
					$this->list[$counter]=new AttachmentRecord();
					$this->list[$counter]->fillRecord($dbconn->row);
					$counter++;
				}
			}
		}
		
		function populateCategPhotos($dbconn,$id,$loc,$typ,$uid,$gid) {
			$counter=0;
			$this->list=null;
	
			$sql="SELECT a.att_id,a.mime,a.URI,a.title,a.width,a.height,a.filesize,a.attorder,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms,a.typ FROM attachment a, cat_photos b WHERE a.att_id=b.att_id AND b.cat_id=".$id." and b.locale=".$loc;
			if($this->by_status!="") $sql.=" AND a.status='".$this->by_status."'";
			if($typ>0) $sql.=" AND a.typ=".$typ;
	                if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY a.attorder";
			 

			if($dbconn->query($sql)) {
				while($dbconn->next_row()) {
					$this->list[$counter]=new AttachmentRecord();
					$this->list[$counter]->fillRecord($dbconn->row);
					$counter++;
				}
			}
		}
	}
?>