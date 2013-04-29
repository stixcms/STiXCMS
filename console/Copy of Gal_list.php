<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(2,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/gallery.php");
		
	$unl=new UsernameList();
	$unl->populate($dbconn);

	$al=new GalleryList();    
//	$al->sort_order="a.locale,a.art_order";
	//$al->getGalleryOfNode($dbconn,$nid+0,$ses_userid,$ses_groupid);
	$al->bycat=$nid+0;
	$al->populate($dbconn);
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
	function doPreview() {
		nw=window.open('../site/content.php?sel=<?=$nid?>','preview','width=780,height=570,scrollbars=yes');	
		nw.focus();
	}
// -->
</SCRIPT>
</HEAD>
<BODY>

<? if($nid>0) {	?>

<h2>Galleries<?=$nname?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(11,$BIT_ADD) && $nid>0) { ?><a href=gal_form.php?aid=0&nid=<?=$nid?>>[add new...]</a><? } ?></h2>
<?
	if($art_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Title</th><th>Status</th><th>Last update</th></tr>\n";
	for($i=0; $i<$art_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	
	<td><?=$al->list[$i]->title?> <? if(is_allowed(2,$BIT_MOD)) { ?><a href=gal_form.php?aid=<?=$al->list[$i]->gal_id?>&nid=<?=$nid?>><? } ?>[edit...]</a></td>
	<td><?=$in_galstat[$al->list[$i]->status]?></td>
	<td><?=timestamp2date($al->list[$i]->lastupdated)?> by <?=$unl->which($al->list[$i]->lastupdatedby)?></td>	
	</tr>
<?
	}
	if($art_count>0) echo "</table>\n"; else echo "No galleries.";
?>


<div align=right>
<a href=# onClick=doPreview();>[preview this node...]</a>
</div>

<? } ?>

</BODY>
</HTML>