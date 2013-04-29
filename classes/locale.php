<?
/////////////////////////////
//                         //
//     locale Objects      //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

	Class LocaleRecord {
	        var $locale;
	        var $name;
	        var $iso;
		var $is_default;
		
	        function fillRecord($array) {
	                $this->locale=$array[0];
	                $this->name=$array[1];
	                $this->iso=$array[2];
	        }
	}
	
	Class LocaleDBRecord extends LocaleRecord {
	        var $dbconn;
	
	        function LocaleDBRecord($dbc) {
	                $this->dbconn=$dbc;
	        }
	
	        function get() {
	                $sql="SELECT locale,name,iso FROM locale WHERE locale=".$this->locale;
	                if($this->dbconn->query($sql))
	                        while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	        }
	
	        function insert() {
	                $sql="INSERT INTO locale (name,iso) VALUES ('".$this->name."','".$this->iso."')";
	                $success=$this->dbconn->query($sql);
	                if($success) $this->locale=$this->dbconn->insert_id();
	                return $success;
	        }
	
	        function update() {
	                $sql="UPDATE locale SET name='".$this->name."',iso='".$this->iso."' WHERE locale=".$this->locale;
	                $success=$this->dbconn->query($sql);
	                return $success;
	        }
	
	        function delete() {
	                $sql="DELETE FROM locale WHERE locale=".$this->locale;
	                $success=$this->dbconn->query($sql);
	                if($success) {
	                	$sql="DELETE FROM articles WHERE locale=".$this->locale;
	                	$this->dbconn->query($sql);
	                	$sql="DELETE FROM module WHERE locale=".$this->locale;
	                	$this->dbconn->query($sql);
	                	$sql="DELETE FROM cat_loc WHERE locale=".$this->locale;
	                	$this->dbconn->query($sql);
	                }
	                return $success;
	        }
	}
	
	Class LocaleList {
	        var $list;
	
	        function populate($dbconn) {
	        	global $DEFLOC;
	                $counter=0;
	                $this->list=null;
	
//			$sql="SELECT def_locale FROM config";
//			if($dbconn->query($sql)) while($dbconn->next_row()) $defloc=$dbconn->row[0];

	                $sql="SELECT locale,name,iso FROM locale ORDER BY locale";
	                if($dbconn->query($sql)) {
	                        while($dbconn->next_row()) {
	                                $this->list[$counter]=new LocaleRecord();
	                                $this->list[$counter]->fillRecord($dbconn->row);
	                                if($this->list[$counter]->locale==$DEFLOC) $this->list[$counter]->is_default=true;
	                                $counter++;
	                        }
	                }
	                
	        }
	        
		function getName($id) {
			for($i=0; $i<count($this->list); $i++) 
				if($this->list[$i]->locale==$id) return $this->list[$i]->name;
			return "";
		}	        
	
	}
?>