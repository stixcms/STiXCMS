<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/usergroup.php");

	$unl=new UserNameList();
	$unl->populate($dbconn);

	$ugl=new UsergroupList();
	$ugl->populate($dbconn);
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Users</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<table border=0 width=100%>
<tr>
<td valign=top>

<h2>Users :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(0,$BIT_ADD)) { ?><a href=user_form.php?uid=0>[add new...]</a><? } ?></h2>
<?
	if(count($unl->users)>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Username</th><th>Name</th><th>Group</th><th>Status</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<count($unl->users); $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$unl->users[$i]->username?></td>
	<td><?=$unl->users[$i]->name?></td>
	<td><?=$ugl->getName($unl->users[$i]->group_id)?></td>
	<td><?=$in_userstat[$unl->users[$i]->status]?></td>
	<td><? if(is_allowed(0,$BIT_MOD)) { ?><a href=user_form.php?uid=<?=$unl->users[$i]->user_id?>>[edit...]</a><? } ?></td>
	</tr>
<?
	}
	if(count($unl->users)>0) echo "</table>\n";
?>

</td>
<td valign=top>

<h2>User Group :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(0,$BIT_ADD)) { ?><a href=usergroup_form.php?ugid=0>[add new...]</a><? } ?></h2>
<?
	if(count($ugl->list)>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Group name</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<count($ugl->list); $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$ugl->list[$i]->name?></td>
	<td><? if(is_allowed(0,$BIT_MOD)) { ?><a href=usergroup_form.php?ugid=<?=$ugl->list[$i]->group_id?>>[edit...]</a><? } ?></td>
	</tr>
<?
	}
	if(count($ugl->list)>0) echo "</table>\n";
?>

</td>
</tr>
</table>
</BODY>
</HTML>