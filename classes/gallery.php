<?
/////////////////////////////
//                         //
//    Gallery Objects     //
//                         //
/////////////////////////////
// Code by Spiros Trivizas //
//       stsoft@mailbox.gr //
/////////////////////////////

Class GalleryRecord {
	var $gal_id;
	var $photos_per_page;
	var $photos_per_line;
	var $thumbnail_width;
	var $thumbnail_height;
	var $title;
	var $status;
	var $createdate;
	var $createdby;
	var $lastupdated;
	var $lastupdatedby;
	var $OwnerUserID;
	var $OwnerGroupID;
	var $RowPerms;
	var $cat_id;
	// gallery_att fields
	var $galatt_id;
	var $att_id1;
	var $att_id2;
	var $attorder;
    var $atext;

	function fillRecord($array) {
		$this->gal_id=$array[0];
		$this->photos_per_page=$array[1];
		$this->photos_per_line=$array[2];
		$this->thumbnail_width=$array[3];
		$this->thumbnail_height=$array[4];
		$this->title=$array[5];
		$this->status=$array[6];
		$this->createdate=$array[7];
		$this->createdby=$array[8];
		$this->lastupdated=$array[9];
		$this->lastupdatedby=$array[10];
		$this->OwnerUserID=$array[11];
		$this->OwnerGroupID=$array[12];
		$this->RowPerms=$array[13];
		$this->cat_id=$array[14];
	}

	function fillrecord2($array)
	{
		$this->gal_id=$array[0];
		$this->galatt_id=$array[1];
		$this->att_id1=$array[2];
		$this->att_id2=$array[3];
		$this->attorder=$array[4];
        $this->atext=$array[5];
	}

	function copyFromObject($obj) {
		$this->gal_id=$obj->gal_id;
		$this->photos_per_page=$obj->photos_per_page;
		$this->photos_per_line=$obj->photos_per_line;
		$this->thumbnail_width=$obj->thumbnail_width;
		$this->thumbnail_height=$obj->thumbnail_height;
		$this->title=$obj->title;
		$this->status=$obj->status;
		$this->createdate=$obj->createdate;
		$this->createdby=$obj->createdby;
		$this->lastupdated=$obj->lastupdated;
		$this->lastupdatedby=$obj->lastupdatedby;
		$this->OwnerUserID=$obj->OwnerUserID;
		$this->OwnerGroupID=$obj->OwnerGroupID;
		$this->RowPerms=$obj->RowPerms;
		$this->cat_id=$obj->cat_id;
	}
}

Class GalleryDBRecord extends GalleryRecord {
	var $dbconn;

	function GalleryDBRecord($dbc) {
		$this->dbconn=$dbc;
	}

	function get() {
		$sql="SELECT gal_id,photos_per_page,photos_per_line,thumbnail_width,thumbnail_height,title,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,cat_id FROM gallery WHERE gal_id=".$this->gal_id." ";
		if($this->dbconn->query($sql))
			while($this->dbconn->next_row()) $this->fillRecord($this->dbconn->row);
	}

	function insert() {
		$sql="INSERT INTO gallery (photos_per_page,photos_per_line,thumbnail_width,thumbnail_height,title,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,cat_id) VALUES (".$this->photos_per_page.",".$this->photos_per_line.",".$this->thumbnail_width.",".$this->thumbnail_height.",'".$this->title."','".$this->status."',".$this->createdate.",".$this->createdby.",".$this->lastupdated.",".$this->lastupdatedby.",".$this->OwnerUserID.",".$this->OwnerGroupID.",'".$this->RowPerms."',".$this->cat_id.")";
	//	die($sql);
		$success=$this->dbconn->query($sql);
		if($success) $this->gal_id=$this->dbconn->insert_id();
		return $success;
	}

	function update() {
		$sql="UPDATE gallery SET photos_per_page=".$this->photos_per_page.",photos_per_line=".$this->photos_per_line.",thumbnail_width=".$this->thumbnail_width.",thumbnail_height=".$this->thumbnail_height.",title='".$this->title."',status='".$this->status."',createdate=".$this->createdate.",createdby=".$this->createdby.",lastupdated=".$this->lastupdated.",lastupdatedby=".$this->lastupdatedby.",OwnerUserID=".$this->OwnerUserID.",OwnerGroupID=".$this->OwnerGroupID.",RowPerms='".$this->RowPerms."',cat_id=".$this->cat_id." WHERE gal_id=".$this->gal_id." ";
		$success=$this->dbconn->query($sql);
		return $success;
	}

	function delete() {
		$sql="DELETE FROM gallery WHERE gal_id=".$this->gal_id." ";
		$success=$this->dbconn->query($sql);
		return $success;
	}

	function getAtt()
	{
	$sql="SELECT gal_id,galatt_id,att_id1,att_id2,attorder,atext FROM gallery_att where galatt_id=".$this->galatt_id;

	if($this->dbconn->query($sql))
			while($this->dbconn->next_row()) $this->fillRecord2($this->dbconn->row);
	}

	function attach_related() {
		if ($this->attorder=="")
		{
			$sql2="select count(*) from gallery_att where gal_id=".$this->gal_id;
		
			if($this->dbconn->query($sql2))	while($this->dbconn->next_row()) $ord=$this->dbconn->row[0];
			
			if (($ord==0)||($ord=="")) $ord=10; else {$ord++;$ord*=10;}
			$this->attorder=$ord;
		}
		$sql="INSERT INTO gallery_att (gal_id,att_id1,att_id2,attorder,atext) VALUES (".$this->gal_id.",".$this->att_id1.",".$this->att_id2.",".$this->attorder.",'".$this->atext."')";

		$success=$this->dbconn->query($sql);
		if($success) $this->gal_id=$this->dbconn->insert_id();
		return $success;
	}
	
	function detach_related()
	{
		$sql="DELETE FROM gallery_att where galatt_id=".$this->galatt_id;
		$success=$this->dbconn->query($sql);
		return $success;
	}
	
}

Class GalleryList {
	var $list;
	var $bycat;
	var $byid;
	
	function populate($dbconn) {
		$counter=0;
		$this->list=null;

		$sql="SELECT gal_id,photos_per_page,photos_per_line,thumbnail_width,thumbnail_height,title,status,createdate,createdby,lastupdated,lastupdatedby,OwnerUserID,OwnerGroupID,RowPerms,cat_id FROM gallery where 1";
		if ($this->bycat!="") $sql.=" and cat_id=".$this->bycat;

        if($dbconn->query($sql)) {
			while($dbconn->next_row()) {
				$this->list[$counter]=new GalleryRecord();
				$this->list[$counter]->fillRecord($dbconn->row);
				$counter++;
			}
		}
	}
	
		function getRelAtts($dbconn) {
		$counter=0;
		$this->list=null;

		$sql="SELECT gal_id,galatt_id,att_id1,att_id2,attorder,atext FROM gallery_att where 1";
		if ($this->byid!="") $sql.=" and gal_id=".$this->byid; 
		$sql.=" order by attorder";
	
		if($dbconn->query($sql)) {
			while($dbconn->next_row()) {
				$this->list[$counter]=new GalleryRecord();
				$this->list[$counter]->fillRecord2($dbconn->row);
				$counter++;
			}
		}
	}
	
		
			

}
?>