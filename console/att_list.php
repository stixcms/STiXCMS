<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if($typ==$ATTYPE_MEDIA && !is_allowed(3,$BIT_READ)) SendError("Not allowed!");
 	if($typ==$ATTYPE_FILES && !is_allowed(4,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/attachment.php");

	$unl=new UsernameList();
	$unl->populate($dbconn);

	$al=new AttachmentList();    
	$al->by_status="A";
	$al->getAttOfNode($dbconn,$nid+0,$typ,$ses_userid,$ses_groupid);
	$att_count=count($al->list);
	
	if($nname!="") $nname=" of ".$nname;
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Attachments</TITLE>
<?=getStyleSheet()?>


<SCRIPT Language=Javascript>
<!--
	function doAttach(id) {
<?	if($insertit) {	?>
		parent.opener.doInsertImageAction(id);
		parent.opener.focus();
		parent.close();
<?	} else {	?>
		location.href='attach.php?pid='+parent.pid+'&rid='+id+'&rt='+parent.rt;
<?	}	?>		
	}
// -->
</SCRIPT>

</HEAD>
<BODY onLoad=self.focus();>


<h2>Contents<?=$nname?></h2>
<?
	if($att_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Title</th><th>Type</th><th>Size (bytes)</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<$att_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><a href="<?=$IN_MEDIA_URL?><?=$al->list[$i]->URI?>" target=viewer> <?=$al->list[$i]->title?> </a></td>
	<td><?=$al->list[$i]->mime?></td>
	<td align=right><?=$al->list[$i]->filesize?></td>
<?	if($insertit) {	?>
	<td><a href=# onClick="doAttach('<?=$al->list[$i]->URI?>');">[insert]</a></td>
<?	} else {	?>
	<td><a href=# onClick="doAttach(<?=$al->list[$i]->att_id?>);">[attach]</a></td>
<?	}	?>	
	</tr>
<?
	}
	if($att_count>0) echo "</table>\n";
?>


</BODY>
</HTML>