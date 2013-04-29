<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(2,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/articles.php");
	require_once("../classes/locale.php");

		
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);

	$unl=new UsernameList();
	$unl->populate($dbconn);

	$al=new ArticleList();    
	$al->sort_order="a.locale,a.art_order";
	$al->getArticlesOfNode($dbconn,$nid+0,$ses_userid,$ses_groupid);
	$art_count=count($al->list);
	
	if($nname!="") $nname=" for ".$nname;
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Articles</TITLE>
<?=getStyleSheet()?>


<SCRIPT Language=Javascript>
<!--
	function doAttach(id) {
		location.href='attach.php?pid='+parent.pid+'&rid='+id+'&rt='+parent.rt;
	}
// -->
</SCRIPT>

</HEAD>
<BODY>


<h2>Articles<?=$nname?></h2>
<?
	if($art_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Locale</th><th>Title</th><th>Status</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<$art_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699;" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$locl->getName($al->list[$i]->locale)?></td>
	<td><?=$al->list[$i]->title?></td>
	<td><?=$in_artstat[$al->list[$i]->status]?></td>
	<td><a href=# onClick="doAttach(<?=$al->list[$i]->art_id?>);">[attach]</a></td>	
	</tr>
<?
	}
	if($art_count>0) echo "</table>\n";
?>


</BODY>
</HTML>