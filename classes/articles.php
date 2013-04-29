<?
/////////////////////////////
//                         //
//    articles Objects     //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class ArticleRecord {
	        var $art_id;
	        var $title;
	        var $shortdesc;
	        var $fulltxt;
	        var $startdate;
	        var $enddate;
	        var $art_order;
	        var $locale;
	        var $status;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	
	        function fillRecord($array) {
	                $this->art_id=$array[0];
	                $this->title=$array[1];
	                $this->shortdesc=$array[2];
	                $this->fulltxt=$array[3];
	                $this->startdate=$array[4];
	                $this->enddate=$array[5];
	                $this->art_order=$array[6];
	                $this->locale=$array[7];
	                $this->status=$array[8];
	                $this->createdate=$array[9];
	                $this->createdby=$array[10];
	                $this->lastupdated=$array[11];
	                $this->lastupdatedby=$array[12];
	  		$this->OwnerUserID=$array[13];
	  		$this->OwnerGroupID=$array[14];
	  		$this->RowPerms=$array[15];		                
	        }
	}
	
	Class ArticleDBRecord extends ArticleRecord {
	        var $dbconn;
	
	        function ArticleDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT art_id,title,shortdesc,fulltxt,startdate,enddate,art_order,locale,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms FROM articles WHERE art_id=".$this->art_id." ";
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }

	        function getHomepageLatest($loc) {
	                $sql="SELECT art_id,title,shortdesc,fulltxt,startdate,enddate,art_order,locale,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms FROM articles WHERE status='3' AND locale=".$loc." ORDER BY lastupdated ASC";
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	        	
	        function insert() {
	                $sql="INSERT INTO articles (title,shortdesc,fulltxt,startdate,enddate,art_order,locale,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms) VALUES ('".$this->title."','".$this->shortdesc."','".$this->fulltxt."',".$this->startdate.",".$this->enddate.",".$this->art_order.",".$this->locale.",'".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",'".$this->RowPerms."')";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->art_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE articles SET title='".$this->title."',shortdesc='".$this->shortdesc."',fulltxt='".$this->fulltxt."',startdate=".$this->startdate.",enddate=".$this->enddate.",art_order=".$this->art_order.",locale=".$this->locale.",status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.", OwnerGroupID=".$this->OwnerGroupID.", RowPerms='".$this->RowPerms."' WHERE art_id=".$this->art_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM articles WHERE art_id=".$this->art_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }

		function attach2node($id) {
			$sql="INSERT INTO art_cat (cat_id,art_id) VALUES (".$id.", ".$this->art_id.")";
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}
			
		function getCategory() {
			$ret=-1;
			$sql="SELECT cat_id FROM art_cat WHERE art_id=".$this->art_id." ORDER BY cat_id DESC";
			if($this->dbconn->query($sql)) 
				while($this->dbconn->next_row()) $ret=$this->dbconn->row[0];
			return $ret;
		}
				
		function attach_related($rid,$rt) {
			$sql="INSERT INTO related (pri_id,rel_id,rel_type) VALUES (".$this->art_id.", ".$rid.",".$rt.")";
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}

		function attach_key($kid) {
			$sql="INSERT INTO art_key (art_id,key_id) VALUES (".$this->art_id.", ".$kid.")";
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}
				
		function detach_related($rid,$rt) {
			$sql="DELETE FROM related WHERE pri_id=".$this->art_id." AND rel_id=".$rid." AND rel_type=".$rt;
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}
		
		function detach_key($kid) {
			$sql="DELETE FROM art_key WHERE art_id=".$this->art_id." AND key_id=".$kid;
			$succ=$this->dbconn->query($sql);
			if(!$succ) return false; else return true;
		}

	}
	
	Class ArticleList {
	        var $list;
		var $full_info;
		var $only_active_today;
		var $by_status;
		var $status_comp;
		var $sort_order;
		var $by_locale;

		function ArticleList() {
			$this->full_info=false;
			$this->only_active_today=false;
			$this->by_status="";
			$this->status_comp="=";
			$this->sort_order="a.art_order";
		}

		function getArticlesOfNode($dbconn,$id,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	                			
			if($this->full_info) $sql.="SELECT a.art_id,a.title,a.shortdesc,a.fulltxt,a.startdate,a.enddate,a.art_order,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			else $sql="SELECT a.art_id,a.title,a.shortdesc,'',0,0,0,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM articles a, art_cat b WHERE a.art_id=b.art_id AND b.cat_id=".$id;
			if($this->only_active_today) $sql.=" AND (a.startdate<=".time()." OR a.startdate=0) AND (a.enddate>=".time()." OR a.enddate=0)";
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			if($this->by_locale!="") $sql.=" AND a.locale=".$this->by_locale;
			if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY ".$this->sort_order;

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new ArticleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
		}
			
		function getRelatedArticles($dbconn,$id,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	                			
			if($this->full_info) $sql.="SELECT a.art_id,a.title,a.shortdesc,a.fulltxt,a.startdate,a.enddate,a.art_order,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			else $sql="SELECT a.art_id,a.title,a.shortdesc,'',0,0,0,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM articles a, related b WHERE a.art_id=b.rel_id AND b.rel_type=1 AND b.pri_id=".$id;
			if($this->only_active_today) $sql.=" AND (a.startdate<=".time()." OR a.startdate=0) AND (a.enddate>=".time()." OR a.enddate=0)";
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			if($this->by_locale!="") $sql.=" AND a.locale=".$this->by_locale;
			if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY ".$this->sort_order;
									
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new ArticleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
		}

		function getAllArticles($dbconn,$uid,$gid) {
	                $counter=0;
	                $this->list=null;
	                			
			if($this->full_info) $sql.="SELECT a.art_id,a.title,a.shortdesc,a.fulltxt,a.startdate,a.enddate,a.art_order,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			else $sql="SELECT a.art_id,a.title,a.shortdesc,'',0,0,0,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM articles a WHERE a.art_id>0";
			if($this->only_active_today) $sql.=" AND (a.startdate<=".time()." OR a.startdate=0) AND (a.enddate>=".time()." OR a.enddate=0)";
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			if($this->by_locale!="") $sql.=" AND a.locale=".$this->by_locale;
			if($uid!=1) $sql.=" AND ( (a.OwnerUserID=".$uid." AND (MID(a.RowPerms,1,1) AND 1) ) OR (a.OwnerGroupID=".$gid." AND (MID(a.RowPerms,2,1) AND 1) ) OR (MID(a.RowPerms,3,1) AND 1) )";
			$sql.=" ORDER BY ".$this->sort_order;

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new ArticleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
		}		
					
		function searchfull($dbconn,$keyword) {
	                $counter=0;
	                $this->list=null;
	                			
			if($this->full_info) $sql.="SELECT a.art_id,a.title,a.shortdesc,a.fulltxt,a.startdate,a.enddate,a.art_order,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			else $sql="SELECT a.art_id,a.title,a.shortdesc,'',0,0,0,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM articles a WHERE (a.title LIKE '%".$keyword."%' OR a.shortdesc LIKE '%".$keyword."%' OR a.fulltxt LIKE '%".$keyword."%')";
			if($this->only_active_today) $sql.=" AND (a.startdate<=".time()." OR a.startdate=0) AND (a.enddate>=".time()." OR a.enddate=0)";
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			if($this->by_locale!="") $sql.=" AND a.locale=".$this->by_locale;
			$sql.=" ORDER BY ".$this->sort_order;

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new ArticleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }			
		}
			
		function search($dbconn,$keyword) {
	                $counter=0;
	                $this->list=null;
	                			
			if($this->full_info) $sql.="SELECT a.art_id,a.title,a.shortdesc,a.fulltxt,a.startdate,a.enddate,a.art_order,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			else $sql="SELECT a.art_id,a.title,a.shortdesc,'',0,0,0,a.locale,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,a.OwnerUserID,a.OwnerGroupID,a.RowPerms";
			$sql.=" FROM articles a, art_key b WHERE a.art_id=b.key_id AND b.keyword='".$keyword."'";
			if($this->only_active_today) $sql.=" AND (a.startdate<=".time()." OR a.startdate=0) AND (a.enddate>=".time()." OR a.enddate=0)";
			if($this->by_status!="") $sql.=" AND a.status".$this->status_comp."'".$this->by_status."'";
			if($this->by_locale!="") $sql.=" AND a.locale=".$this->by_locale;
			$sql.=" ORDER BY ".$this->sort_order;

	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new ArticleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }			
		}

	}
?>