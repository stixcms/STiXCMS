<?
	require_once("../common/globals.php");

	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");
	
 	if(!is_allowed(5,$BIT_READ)) SendError("Not allowed!");	

	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/template.php");

	$unl=new UsernameList();
	$unl->populate($dbconn);

	$tl=new TemplateList();    
	$tl->populate($dbconn,$ses_userid,$ses_groupid);
	$temp_count=count($tl->list); 	
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Ads</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY onLoad="top.nav.setSel('td4');">

<h2>Templates&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(5,$BIT_ADD)) { ?><a href=temp_form.php?tid=0>[add new...]</a><? } ?></h2>
<?
	if($temp_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Title</th><th>Status</th><th>Last update</th></tr>\n";
	for($i=0; $i<$temp_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$tl->list[$i]->title?> <? if(is_allowed(5,$BIT_MOD)) { ?><a href=temp_form.php?tid=<?=$tl->list[$i]->temp_id?>><? } ?>[edit...]</a></td>
	<td><?=$in_tempstat[$tl->list[$i]->status]?></td>
	<td><?=timestamp2date($tl->list[$i]->lastupdated)?> by <?=$unl->which($tl->list[$i]->lastupdatedby)?></td>	
	</tr>
<?
	}
	if($temp_count>0) echo "</table>\n";
?>


</BODY>
</HTML>
