<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(9,$BIT_ADD) && $mid<=0) SendError("Not allowed!");
	if(!is_allowed(9,$BIT_MOD) && $mid>0) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,2)) SendError("Not allowed!");	
	
	


	if(is_uploaded_file($mfile)) {
			if(!move_uploaded_file($mfile,$IN_MODULE_DIR.$mfile_name)) SendError("Unable to move file!");
			$file_uploaded=true;
	} else {
		$file_uploaded=false;
	}

	
	require_once("../common/connect.php");
	require_once("../classes/module.php");

	
	$mr=new ModuleDBRecord($dbconn);
	
	$mr->mod_id=$mid;
	$mr->cat_id=$nid;
	$mr->locale=$mloc;
	if($file_uploaded) $mr->URI=$mfile_name; else $mr->URI=$muri;
	$mr->title=$mtitle;
	$mr->status=$mstatus;
	$mr->place=$mplace;
	if($morder!="") $mr->modorder=$morder; else $mr->modorder=10;
	$mr->lastupdatedby=$ses_userid;
	$mr->lastupdated=time();
	$mr->createdate=($mid>0)?$ccreated:time();
	$mr->createdby=($mid>0)?$ccreateu:$ses_userid;
	$mr->OwnerUserID=$ouid;	
	$mr->OwnerGroupID=$ogid;
	$mr->RowPerms=$rperms;
		
	if($mid<=0) $success=$mr->insert(); else $success=$mr->update();
	

	if(!$success) SendError("DB error!"); else
?>
<HTML>
<HEAD>	
<TITLE>Modify Module</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Το Module αποθηκεύθηκε!</h1>
	<BR><BR>
	<a href=mod_form.php?mid=<?=$mr->mod_id?>&nid=<?=$nid?>>[επιστροφή στη φόρμα]</a>		
</CENTER>

</BODY>
</HTML>