<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD)) SendError("Not allowed!");
	
		
	require_once("../common/connect.php");
	require_once("../classes/gallery.php");
	
	$ar=new GalleryDBRecord($dbconn);
	$ar->gal_id=$gal_id;
	$ar->galatt_id=$galattid;
	$ar->att_id1=$att1;
	$ar->att_id2=$att2;
	$ar->attorder=$aorder;
    $ar->atext=$atxt;
	$success=$ar->attach_related($rid,$rt);
	
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<TITLE>Modify Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Related material has been attached successfully!</h1>
	<BR><BR>
   	<a href=# onClick="location.href='gal_form.php?aid=<?=$gal_id?>&nid=<?=$nid?>'">return to the Gallery Form</a>	.
</CENTER>

</BODY>
</HTML>