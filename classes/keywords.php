<?
/////////////////////////////
//                         //
//    keywords Objects     //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class KeywordsRecord {
	        var $key_id;
	        var $keyword;
	        var $locale;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	
	        function fillRecord($array) {
	                $this->key_id=$array[0];
	                $this->keyword=$array[1];
	                $this->locale=$array[2];
	                $this->createdate=$array[3];
	                $this->createdby=$array[4];
	                $this->lastupdated=$array[5];
	                $this->lastupdatedby=$array[6];
	        }
	}
	
	Class KeywordsDBRecord extends KeywordsRecord {
	        var $dbconn;
	
	        function KeywordsDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT key_id,keyword,locale,createdate,createdby,lastupdated,lastupdatedby FROM keywords WHERE key_id=".$this->key_id." ";
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO keywords (keyword,locale,createdate,createdby,lastupdated,lastupdatedby) VALUES ('".$this->keyword."',".$this->locale.",".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.")";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->key_id=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE keywords SET keyword='".$this->keyword."',locale=".$this->locale.",createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby." WHERE key_id=".$this->key_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM keywords WHERE key_id=".$this->key_id;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	}
	
	Class KeywordsList {
	        var $list;
		var $loc;
		
	        function populate($dbconn) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT key_id,keyword,locale,createdate,createdby,lastupdated,lastupdatedby FROM keywords";
	                if($this->loc!="") $sql.=" WHERE locale=".$this->loc;
	                $sql.=" ORDER BY locale,keyword";
	
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new KeywordsRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }

	        function ofArticle($dbconn,$id) {
	                $counter=0;
	                $this->list=null;
	
	                $sql="SELECT a.key_id,a.keyword,a.locale,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby FROM keywords a , art_key b WHERE a.key_id=b.key_id AND b.art_id=".$id;
	                $sql.=" ORDER BY a.locale,a.keyword";
	
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new KeywordsRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                $counter++;
	                        }
	                }
	        }	
	}
?>