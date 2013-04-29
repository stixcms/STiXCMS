<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(2,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/articles.php");
	require_once("../classes/locale.php");
	require_once("../classes/module.php");
		
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);
	
	$unl=new UsernameList();
	$unl->populate($dbconn);

	$al=new ArticleList();    
	$al->sort_order="a.locale,a.art_order";
	$al->getArticlesOfNode($dbconn,$nid+0,$ses_userid,$ses_groupid);
	$art_count=count($al->list);
	
	$ml=new ModuleList();
	$ml->of_node=$nid+0;
	$ml->populate($dbconn,$ses_userid,$ses_groupid);
	$mod_count=count($ml->list);
	
	if($nname!="") $nname=" for ".$nname;
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Articles</TITLE>
<?=getStyleSheet()?>

<SCRIPT Language=Javascript>
<!--
	function doPreview() {
		nw=window.open('../site/content.php?sel=<?=$nid?>','preview','width=780,height=570,scrollbars=yes');	
		nw.focus();
	}
// -->
</SCRIPT>
</HEAD>
<BODY>

<? if($nid>0) {	?>

<h2>Articles<?=$nname?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(2,$BIT_ADD) && $nid>0) { ?><a href=art_form.php?aid=0&nid=<?=$nid?>>[add new...]</a><? } ?></h2>
<?
	if($art_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Locale</th><th>Title</th><th>Status</th><th>Last update</th></tr>\n";
	for($i=0; $i<$art_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$locl->getName($al->list[$i]->locale)?></td>
	<td><?=$al->list[$i]->title?> <? if(is_allowed(2,$BIT_MOD)) { ?><a href=art_form.php?aid=<?=$al->list[$i]->art_id?>&nid=<?=$nid?>><? } ?>[edit...]</a></td>
	<td><?=$in_artstat[$al->list[$i]->status]?></td>
	<td><?=timestamp2date($al->list[$i]->lastupdated)?> by <?=$unl->which($al->list[$i]->lastupdatedby)?></td>	
	</tr>
<?
	}
	if($art_count>0) echo "</table>\n"; else echo "No articles.";
?>

<br><br>
<h2>Modules<?=$nname?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(9,$BIT_ADD) && $nid>0) { ?><a href=mod_form.php?mid=0&nid=<?=$nid?>>[add new ...]</a><? } ?></h2>
<?
		if($mod_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Locale</th><th>Title</th><th>Status</th><th>Last update</th></tr>\n";
		for($i=0; $i<$mod_count; $i++) {
?>	
		<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
		<td><?=$locl->getName($ml->list[$i]->locale)?></td>
		<td><?=$ml->list[$i]->title?> <? if(is_allowed(9,$BIT_MOD)) { ?><a href=mod_form.php?mid=<?=$ml->list[$i]->mod_id?>&nid=<?=$nid?>><? } ?>[edit...]</a></td>
		<td><?=$in_modstat[$ml->list[$i]->status]?></td>
		<td><?=timestamp2date($ml->list[$i]->lastupdated)?> by <?=$unl->which($ml->list[$i]->lastupdatedby)?></td>	
		</tr>
<?
		}
		if($mod_count>0) echo "</table>\n"; else echo "No modules.";
?>


<br>
<div align=right>
<a href=# onClick=doPreview();>[preview this node...]</a>
</div>

<? } ?>

</BODY>
</HTML>