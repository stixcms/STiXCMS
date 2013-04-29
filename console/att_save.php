<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(3,$BIT_ADD) && $aid<=0) SendError("Not allowed!");
	if(!is_allowed(3,$BIT_MOD) && $aid>0) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,2)) SendError("Not allowed!");	
	
	
	require_once("../classes/imgsize.php");



	function constructFilePath($fn) {
		global $IN_MEDIA_DIR;
		$ok=true;
		$ex=strtoupper(substr(strstr($fn,"."),1));
		$let=strtoupper(substr($fn,0,1));
	
		if(!is_dir($IN_MEDIA_DIR.$ex)) $ok=mkdir($IN_MEDIA_DIR.$ex,0777);
		if($ok) {
			if(!is_dir($IN_MEDIA_DIR.$ex."/".$let)) $ok=mkdir($IN_MEDIA_DIR.$ex."/".$let,0777);
			if($ok) return $ex."/".$let."/";
		}
		return "";
	}

	function createThumbName($fn) {
		$ex=strstr($fn,".");
		$ret=substr($fn,0,strlen($fn)- strlen($ex))."-THUMB".$ex;
		return $ret;
	}
		
	if(is_uploaded_file($mfile)) {
			$fpath=constructFilePath($mfile_name);
			if($fpath=="") SendError("Unable to determine physical folder!");
		
			if(!move_uploaded_file($mfile,$IN_MEDIA_DIR.$fpath.$mfile_name)) SendError("Unable to move file!");

			$f=new ImgSize($IN_MEDIA_DIR.$fpath.$mfile_name);
			if($mwidth<=0) $mwidth=$f->width;
			if($mheight<=0) $mheight=$f->height;
			$mfilesize=$mfile_size;
			$mmime=$mfile_type;		
			
			$file_uploaded=true;
	} else {
		$file_uploaded=false;
	}

	
	require_once("../common/connect.php");
	require_once("../classes/attachment.php");

	
	$mr=new AttachmentDBRecord($dbconn);
	
	$mr->att_id=$aid;
	$mr->mime=$mmime;
	if($file_uploaded) $mr->URI=$fpath.$mfile_name; else $mr->URI=$muri;
	$mr->title=$mtitle;
	$mr->status=$mstatus;
	$mr->width=$mwidth+0;
	$mr->height=$mheight+0;
	$mr->filesize=$mfilesize+0;
	$mr->typ=$mtyp+0;
	if($morder!="") $mr->attorder=$morder; else $mr->attorder=10;
	$mr->lastupdatedby=$ses_userid;
	$mr->lastupdated=time();
	$mr->createdate=($aid>0)?$ccreated:time();
	$mr->createdby=($aid>0)?$ccreateu:$ses_userid;
	$mr->OwnerUserID=$ouid;	
	$mr->OwnerGroupID=$ogid;
	$mr->RowPerms=$rperms;
		
	if($aid<=0) $success=$mr->insert(); else $success=$mr->update();
	if($success) {
		// attach record to a node
		if($aid<=0) $success2=$mr->attach2node($nid);
		
		// rename file to AttID.ext and update record
		if($file_uploaded) {
			$mr->URI=$fpath.$mr->att_id.strstr($mfile_name,".");
			$success2=$mr->update(); 
			if($success2) {
				if($file_uploaded) rename($IN_MEDIA_DIR.$fpath.$mfile_name,$IN_MEDIA_DIR.$mr->URI);
			}
		}
		
		// create thumbnail
		if($createThumb && $thumbsize>0) {
			if(strstr($mr->mime,"jpeg")) {
				$it="j";
				$si=@imagecreatefromjpeg($IN_MEDIA_DIR.$mr->URI);
			} else if(strstr($mr->mime,"png")) {
				$it="p";
				$si=@imagecreatefrompng($IN_MEDIA_DIR.$mr->URI);
			}
			if($si) {
				$dw=intval(($mr->width*$thumbsize)/100);
				$dh=intval(($mr->height*$thumbsize)/100);
				$di=imagecreate($dw,$dh);
				
				/*if(function_exists("imagecopyresampled")) $isc=@imagecopyresampled($di,$si,0,0,0,0,$dw,$dh,$mr->width,$mr->height);	
				else $isc=imagecopyresized($di,$si,0,0,0,0,$dw,$dh,$mr->width,$mr->height);	
				if(!$isc)*/ 
				$isc=imagecopyresized($di,$si,0,0,0,0,$dw,$dh,$mr->width,$mr->height);	
				if($isc) {
					$thumbname=createThumbName($mr->URI);
					if($it=="p") imagepng($si,$IN_MEDIA_DIR.$thumbname);
					else if($it=="j") imagejpeg($si,$IN_MEDIA_DIR.$thumbname);
					$thumbmsg="<br><br>Created thumbnail ".$dw."x".$dh." pixels";
				}
			} else $thumbmsg="<br><br>Thumbnail not created!";
		}
	}
	

	if(!$success) SendError("DB error!"); else
?>
<HTML>
<HEAD>	
<TITLE>Modify File</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Media/File has been saved successfully! <?=$thumbmsg?></h1>
	<BR><BR>
	<a href=att_form.php?aid=<?=$mr->att_id?>&nid=<?=$nid?>>[click to return to media/file]</a>		
</CENTER>

</BODY>
</HTML>