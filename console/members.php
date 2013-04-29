<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(8,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/member.php");
	
	$ml=new MemberList();
	$ml->populate($dbconn);
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Members</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<h2>Members :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(8,$BIT_ADD)) { ?><a href=member_form.php?mid=0>[add new...]</a><? } ?></h2>
<?
	if(count($ml->list)>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Name</th><th>Status</th><th>Reg. date</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<count($ml->list); $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$ml->list[$i]->name?> <?=$ml->list[$i]->lastname?></td>
	<td><?=$in_memstat[$ml->list[$i]->status]?></td>
	<td><?=timestamp2date($ml->list[$i]->date_created)?></td>
	<td><? if(is_allowed(8,$BIT_MOD)) { ?><a href=member_form.php?mid=<?=$ml->list[$i]->member_id?>>[edit...]</a><? } ?></td>
	</tr>
<?
	}
	if(count($ml->list)>0) echo "</table>\n";
?>

</BODY>
</HTML>