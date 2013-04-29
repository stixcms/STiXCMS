<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(7,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/ads.php");

	$unl=new UsernameList();
	$unl->populate($dbconn);

	$al=new AdsList();    
	$al->getAdsOfNode($dbconn,$nid+0,$ses_userid,$ses_groupid);
	$ad_count=count($al->list);
	
	if($nname!="") $nname=" for ".$nname;
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Ads</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>


<h2>Ads<?=$nname?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(6,$BIT_ADD) && $nid>0) { ?><a href=ad_form.php?aid=0&nid=<?=$nid?>>[add new...]</a><? } ?></h2>
<?
	if($ad_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Name</th><th>Status</th><th>Last update</th></tr>\n";
	for($i=0; $i<$ad_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$al->list[$i]->name?> <? if(is_allowed(6,$BIT_MOD)) { ?><a href=ad_form.php?aid=<?=$al->list[$i]->ad_id?>&nid=<?=$nid?>><? } ?>[edit...]</a></td>
	<td><?=$in_adstat[$al->list[$i]->status]?></td>
	<td><?=timestamp2date($al->list[$i]->lastupdated)?> by <?=$unl->which($al->list[$i]->lastupdatedby)?></td>	
	</tr>
<?
	}
	if($ad_count>0) echo "</table>\n";
?>


</BODY>
</HTML>