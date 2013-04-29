<?

	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	//if($nid<=0) SendError("Cannot add an article here!");

	if(!is_allowed(11,$BIT_ADD) ) SendError("Not allowed!");
 	if(!is_allowed(11,$BIT_MOD) ) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/gallery.php");
	require_once("../classes/attachment.php");
		
		$art=new GalleryDBRecord($dbconn);
		$art->galatt_id=$attid;
		$art->get();
?>
<HTML>
<HEAD>	
<TITLE>Gallery Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY !onLoad=switch2html();>

<form name=artf action=gal_save.php method=post>
<input type=hidden name=gal_id value="<?=$art->gal_id?>">
<input type=hidden name=galattid value="<?=$art->galatt_id?>">
<input type=hidden name=att1 value="<?=$art->att_id1?>">
<input type=hidden name=att2 value="<?=$art->att_id2?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Article details form</td>
</tr>
<tr>
<td>Thumbnail:</td>
<td colspan=3>
<?if ($art->att_id1!=""){ ?> <a href="<?=$IN_MEDIA_URL.$art->att_id1?>">View</a><?}?>
<a href=# onClick="Javascript:window.open('gatt_select.php?rt=<?=$ATTYPE_MEDIA?>&pid=<?=$attid?>','','width=610,height=450');">Add/change</a>
<input type=text name=att1 value="">
</td>
</tr>

<td>Photo:</td>
<td colspan=3>
<?if ($art->att_id2!=""){ ?> <a href="<?=$IN_MEDIA_URL.$art->att_id2?>">View</a><?}?>
<a href=# onClick="Javascript:window.open('gatt_select.php?rt=<?=$ATTYPE_MEDIA?>&pid=<?=$attid?>','','width=610,height=450');">Add/change</a>
<input type=text name=att2 value="">
</td>
</tr>

<tr>
<td>order:</td>
<td colspan=3><input type=text  name=atitle size=10 class=form value="<?=$art->attorder?>" onChange=check4quotes(this);></td>
</tr>
</table>
</form>


<? if($aid>0) showRecProps($art->createdate,$art->lastupdated); ?>

</BODY>
</HTML>