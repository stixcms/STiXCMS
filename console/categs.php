<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(1,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	
	$unl=new UsernameList();
	$unl->populate($dbconn);
	
	 	
 	if(!isset($treeparam)) 
 		if(isset($nparam)) $treeparam=$nparam; else $treeparam=1;

	function hasNoLock($n) {
		global $treeparam,$LOCK_FIRST_LEVEL;
		if($treeparam==1 && $LOCK_FIRST_LEVEL && $n<=0) return false;
		return true;
	} 	

	if(!$collapse) {
		session_register("ses_exp");
		session_register("ses_nid");
		if(!isset($exp)) $exp=$ses_exp;
		if($nid<=0) $nid=$ses_nid;
		$ses_exp=$exp;
		$ses_nid=$nid;
	}
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>categories</TITLE>
<?=getStyleSheet()?>
</HEAD>

<SCRIPT Language=Javascript>
<!--
	function handleCat(cid,p) {
		location.href="cat_form.php?id="+cid+"&param="+p;
	}
	
	function doPreview() {
		nw=window.open('../site/content.php?sel=<?=$nid?>','preview','width=780,height=570,scrollbars=yes');	
		nw.focus();
	}	
// -->
</SCRIPT>

<BODY>



<form action=categs.php name=cf>
<h2>Nodes: <select name=treeparam onChange=document.cf.submit();><?
	for($i=1; $i<=count($in_catparam); $i++) {
		echo "<option value=".$i;
		if($i==$treeparam) echo " selected";
		echo ">".$in_catparam[$i]."</option>";	
	}
?></select>&nbsp;&nbsp; <a href=categs.php?expandall=true&treeparam=<?=$treeparam?>>[expand all]</a> - <a href=categs.php?treeparam=<?=$treeparam?>&collapse=1>[collapse all]</a><? if(is_allowed(1,$BIT_ADD) && hasNoLock($nid)) { ?>- <a href="cat_form.php?id=0&nid=<?=$nid?>&param=<?=$treeparam?>">[add new ...]</a><? } ?></h2>
</form>

<table border=0 cellspacing=2 width=100%>
<tr>
<td valign=top>
<?
	$calling_location="categs.php?";
	require_once("tree.php");
?>
</td>
<td valign=top align=center><? if($nid>0) { ?>Children of <?=$sm->getName($nid)?>: <? if($treeparam==1) { ?><a href=# onClick=doPreview();>[preview this node...]</a><? } ?><br><? } ?>
<?
	$ch=$sm->getSubNodes($nid+0,$treeparam);
	$node_count=count($ch);

	if($node_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Name</th><th>Status</th><th>Last update</th></tr>\n";
	for($i=0; $i<$node_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$ch[$i]->name?> <? if(is_allowed(1,$BIT_MOD)) { ?><a href="cat_form.php?id=<?=$ch[$i]->cat_id?>&param=<?=$treeparam?>">[edit...]</a><? } ?></td>
	<td><?=$in_catstat[$ch[$i]->status]?></td>
	<td><?=timestamp2date($ch[$i]->lastupdated)?> by <?=$unl->which($ch[$i]->lastupdatedby)?></td>	
	</tr>
<?
	}
	if($node_count>0) echo "</table>\n"; else echo "No children.";
?>	




</td>
</tr>
</table>

</BODY>
</HTML>