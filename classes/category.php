<?
/////////////////////////////
//                         //
//  Category Objects       //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class CategoryRecord {
		var $cat_id;
		var $parent_id;
		var $cat_order;
		var $cat_param;
		var $status;
	        var $createdate;
	        var $createdby;
	        var $lastupdated;
	        var $lastupdatedby;
	        var $name; // from cat_loc
	        var $temp_id; // from cat_loc
	        
		function fillRecords($array) {
			$this->cat_id=$array[0];
			$this->parent_id=$array[1];
			$this->cat_order=$array[2];
			$this->cat_param=$array[3];
			$this->status=$array[4];
	                $this->createdate=$array[5];
	                $this->createdby=$array[6];
	                $this->lastupdated=$array[7];
	                $this->lastupdatedby=$array[8];
		}
	}
	
	Class CategoryDBRecord extends CategoryRecord {
		var $dbconn;
				
		function CategoryDBRecord($dbc) {
			$this->dbconn=$dbc;	
		}
		
		function get($id) {
			$sql="SELECT cat_id,parent_id,cat_order,cat_param,status,createdate,createdby,lastupdated,lastupdatedby FROM cat WHERE cat_id=".$id;
			if($this->dbconn->query($sql))
				while($this->dbconn->next_row()) $this->fillRecords($this->dbconn->row);
		}
		
		function insert() {
			if($this->cat_order<=0) {
				$sql="SELECT MAX(cat_order) FROM cat WHERE parent_id=".$this->parent_id." AND cat_param=".$this->cat_param;
				if($this->dbconn->query($sql)) {
					while($this->dbconn->next_row()) $this->cat_order=$this->dbconn->row[0] + 10;
				}
			}
			$sql="INSERT INTO cat (parent_id,cat_order,cat_param,status,createdate,createdby,lastupdated,lastupdatedby) VALUES (".$this->parent_id.",".$this->cat_order.",".$this->cat_param.",'".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.")";
			$success=$this->dbconn->query($sql);
			if($success) $this->cat_id=$this->dbconn->insert_id();
			return $success;
		}
		
		function update() {
			$sql="UPDATE cat SET parent_id=".$this->parent_id.", cat_order=".$this->cat_order." ,cat_param=".$this->cat_param.", status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby." WHERE cat_id=".$this->cat_id;
			$success=$this->dbconn->query($sql);
			return $success;
		}
		
		function delete() {
			$haschildren=0;
			$sql="SELECT count(*) FROM cat WHERE parent_id=".$this->cat_id;
			if($this->dbconn->query($sql)) while($this->dbconn->next_row()) $haschildren=$this->dbconn->row[0];
			if($haschildren>0) return false;
			$sql="DELETE FROM cat WHERE cat_id=".$this->cat_id;
			$success=$this->dbconn->query($sql);
			if($success) {
				$sql="DELETE FROM cat_loc WHERE cat_id=".$this->cat_id;
				$this->dbconn->query($sql);
			}
			return $success;
		}
		
		function deleteLoc() {
			$sql="DELETE FROM cat_loc WHERE cat_id=".$this->cat_id;
			$success=$this->dbconn->query($sql);
			return $success;
		}
		
		function attach_related($cid,$lid,$attid,$order)
		{
			if ($order=="")
			{
				$sql2="select count(*) from cat_photos where cat_id=".$cid." and locale=".$lid;
			
				if($this->dbconn->query($sql2))	while($this->dbconn->next_row()) $ord=$this->dbconn->row[0];
				
				if (($ord==0)||($ord=="")) $ord=10; else {$ord++;$ord*=10;}
				$torder=$ord;
			}
			$sql="insert into cat_photos (cat_id,locale,att_id,aorder) values (".$cid.",".$lid.",".$attid.",".$torder.")";
		 
			$success=$this->dbconn->query($sql);
			return $success;
		}
		
		function detach_related($cid,$lid,$attid)
		{
			$sql="delete from cat_photos where cat_id=".$cid." and locale=".$lid." and att_id=".$attid;
			$success=$this->dbconn->query($sql);
			return $success;
		}
	}

	Class CatLocRecord {
		var $cat_id;
		var $locale;
		var $name;
		var $temp_id;
		
		function fillRecords($array) {
			$this->cat_id=$array[0];
			$this->locale=$array[1];
			$this->name=$array[2];
			$this->temp_id=$array[3];
		}
	}

	Class CatLocDBRecord extends CatLocRecord {
		var $dbconn;
				
		function CatLocDBRecord($dbc) {
			$this->dbconn=$dbc;	
		}
		
		function get() {
			$sql="SELECT cat_id,locale,name,temp_id FROM cat_loc WHERE cat_id=".$this->cat_id." AND locale=".$this->locale;
			
			if($this->dbconn->query($sql))
				while($this->dbconn->next_row()) $this->fillRecords($this->dbconn->row);
		}
		
		function insert() {
			$sql="INSERT INTO cat_loc (cat_id,locale,name,temp_id) VALUES (".$this->cat_id.",".$this->locale.",'".$this->name."',".$this->temp_id.")";
			$success=$this->dbconn->query($sql);
			return $success;
		}
		
		function update() {
			$sql="UPDATE cat_loc SET name='".$this->name."', temp_id=".$this->temp_id." WHERE cat_id=".$this->cat_id." AND locale=".$this->locale;
			$success=$this->dbconn->query($sql);
			return $success;
		}
		
		function delete() {
			$sql="DELETE FROM cat_loc WHERE cat_id=".$this->cat_id." AND locale=".$this->locale;
			$success=$this->dbconn->query($sql);
			return $success;
		}
		
	}	
	
	Class CategoryMap {
		var $nodes;
		var $locale;
		
		function CategoryMap($dbconn,$spec_status="",$spec_comp="",$spec_param="",$loc="") {
			global $DEFLOC;
			if($loc=="") $this->locale=$DEFLOC; else $this->locale=$loc;
			$counter=0;
			$sql="SELECT a.cat_id,a.parent_id,a.cat_order,a.cat_param,a.status,a.createdate,a.createdby,a.lastupdated,a.lastupdatedby,b.name,b.temp_id FROM cat a, cat_loc b WHERE a.cat_id=b.cat_id AND b.locale=".$this->locale;
			if($spec_param!="") $sql.=" AND a.cat_param=".$spec_param;
			if($spec_status!="") $sql.=" AND a.status".$spec_comp."'".$spec_status."'";
			$sql.=" ORDER BY a.cat_param,a.parent_id,a.cat_order";
		
			if($dbconn->query($sql)) {
				while($dbconn->next_row()) {
					$this->nodes[$counter]=new CategoryRecord();
					$this->nodes[$counter]->fillRecords($dbconn->row);
					$this->nodes[$counter]->name=$dbconn->row[9];
					$this->nodes[$counter]->temp_id=$dbconn->row[10];
					$counter++;
				}
			}		
		}
		
		function getSubNodes($id,$param=0) {
			if($this->nodes) {
				$counter=0;
				$c=reset($this->nodes);
				while(($c->parent_id!=$id || $c->cat_param!=$param) && $c!=null) $c=next($this->nodes);
				while($c->parent_id==$id && $c->cat_param==$param && $c!=null) {
					$children[$counter]=new CategoryRecord();
					$children[$counter]->cat_id=$c->cat_id;
					$children[$counter]->name=$c->name;
					$children[$counter]->temp_id=$c->temp_id;
					$children[$counter]->cat_order=$c->cat_order;
					$children[$counter]->parent_id=$c->parent_id;
					$children[$counter]->status=$c->status;
					$children[$counter]->lastupdated=$c->lastupdated;
					$children[$counter]->lastupdatedby=$c->lastupdatedby;
					$counter++;
					$c=next($this->nodes);
				}
				return $children;
			} else return null;
		} 
				
		function getPath($id) {
			$temp=$id;
			$counter=0;
			$cleanloop=false;
			while($temp>0 && !$cleanloop) {
				$cleanloop=true;
				for($i=0; $i<count($this->nodes); $i++) {
					if($this->nodes[$i]->cat_id==$temp) {
						$path[$counter++]=$this->nodes[$i];
						$temp=$this->nodes[$i]->parent_id;
						$cleanloop=false;
					}
				}
			}
			
			if(count($path)>0) return array_reverse($path); else return null;
		}

		function getName($id) {
			for($i=0; $i<count($this->nodes); $i++) 
				if($this->nodes[$i]->cat_id==$id) return $this->nodes[$i]->name;
			return "";
		}

		function getParent($id) {
			for($i=0; $i<count($this->nodes); $i++) 
				if($this->nodes[$i]->cat_id==$id) return $this->nodes[$i]->parent_id;
			return 0;
		}

		function getFirstNode() {
			$least=99999;
			$found_i=-1;
			for($i=0; $i<count($this->nodes); $i++) 
				if($this->nodes[$i]->parent_id==0) if($this->nodes[$i]->cat_order<$least) {
					$least=$this->nodes[$i]->cat_order;
					$found_i=$i;
				}
			return $this->nodes[$found_i]->cat_id;
		}
				
		function getTemplate($id) {
			for($i=0; $i<count($this->nodes); $i++)  {
				if($this->nodes[$i]->cat_id==$id) return $this->nodes[$i]->temp_id;
			}
			return 0;
		}
	}

?>