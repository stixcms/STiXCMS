<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD)) SendError("Not allowed!");
	
		
	require_once("../common/connect.php");
	require_once("../classes/gallery.php");
	
	$ar=new GalleryDBRecord($dbconn);
	$ar->galatt_id=$gattid;
	
	$success=$ar->detach_related();
	
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>related material has been detached successfully!</h1>
	<BR><BR>
	<a href=gal_form.php?aid=<?=$gid?>&nid=<?=$nid?>>[return to gallery form]</a>	
</CENTER>

</BODY>
</HTML>