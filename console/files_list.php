<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(4,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/attachment.php");

	$unl=new UsernameList();
	$unl->populate($dbconn);

	$al=new AttachmentList();    
	$al->getAttOfNode($dbconn,$nid+0,$ATTYPE_FILES,$ses_userid,$ses_groupid);
	$att_count=count($al->list);
	
	if($nname!="") $nname=" in ".$nname;
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Files</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>


<h2>Files<?=$nname?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(4,$BIT_ADD) && $nid>0) { ?><a href=att_form.php?aid=0&nid=<?=$nid?>&typ=<?=$ATTYPE_FILES?>>[add new...]</a><? } ?></h2>
<?
	if($att_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Title</th><th>Type</th><th>Size (bytes)</th><th>Status</th><th>Last update</th></tr>\n";
	for($i=0; $i<$att_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$al->list[$i]->title?> <? if(is_allowed(4,$BIT_MOD)) { ?><a href=att_form.php?aid=<?=$al->list[$i]->att_id?>&nid=<?=$nid?>><? } ?>[edit...]</a></td>
	<td><?=$al->list[$i]->mime?></td>
	<td align=right><?=$al->list[$i]->filesize?></td>
	<td><?=$in_attstat[$al->list[$i]->status]?></td>
	<td><?=timestamp2date($al->list[$i]->lastupdated)?> by <?=$unl->which($al->list[$i]->lastupdatedby)?></td>	
	</tr>
<?
	}
	if($att_count>0) echo "</table>\n";
?>


</BODY>
</HTML>