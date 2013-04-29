<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(11,$BIT_MOD) || !is_allowed(11,$BIT_ADD)) SendError("Not allowed!");
	
	if(!allowed2do($ouid,$ogid,$rperms,11)) SendError("Not allowed!");	
		
	require_once("../common/connect.php");
	require_once("../classes/gallery.php");
	
	$ar=new GalleryDBRecord($dbconn);
	
	$ar->gal_id=$aid;
	$ar->title=$atitle;
	$ar->status=$astatus;
	$ar->lastupdatedby=$ses_userid;
	$ar->lastupdated=time();
	$ar->createdate=($aid>0)?$ccreated:time();
	$ar->createdby=($aid>0)?$ccreateu:$ses_userid;
	$ar->OwnerUserID=$ouid;	
	$ar->OwnerGroupID=$ogid;
	$ar->RowPerms=$rperms;
	$ar->photos_per_page=$photoperpage;
	$ar->photos_per_line=$photoperline;
	$ar->thumbnail_width=$thumbwidth;
	$ar->thumbnail_height=$thumbheight;	
	$ar->cat_id=$nid;
	if($aid<=0) $success=$ar->insert(); else $success=$ar->update();
//	if($success && $aid<=0) $success2=$ar->attach2node($nid);
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Gallery</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Gallery has been saved successfully!</h1>
	<BR><BR>
	<a href=gal_form.php?aid=<?=$ar->gal_id?>&nid=<?=$nid?>>[click to return to gallery form]</a>	
	<br>
	<a href=Gal_list.php>[click to return to gallery list]</a>	
</CENTER>

</BODY>
</HTML>