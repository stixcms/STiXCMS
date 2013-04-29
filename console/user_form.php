<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_MOD) || !is_allowed(0,$BIT_ADD)) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/usergroup.php");
	
	$ur=new UserDBRecord($dbconn);
	if($uid>0) $ur->get($uid);

	
	$ugl=new UsergroupList();
	$ugl->populate($dbconn);	
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>User Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>

<form name=userf action=user_save.php method=post>
<input type=hidden name=uid value="<?=$uid?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>User details form</td>
</tr>
<tr>
<td>Group:</td>
<td colspan=3><select name=ugid><? 
	for($i=0; $i<count($ugl->list); $i++) {
		echo "<option value=".$ugl->list[$i]->group_id;
		if($ugl->list[$i]->group_id==$ur->group_id) echo " selected";
		echo ">".$ugl->list[$i]->name."</option>";
	}
?></select></td>
</tr>
<tr>
<td>Username:</td>
<td colspan=3><INPUT TYPE=text size=12 maxlength=16 name=uusername value="<?=$ur->username?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Password:</td>
<td colspan=3><INPUT TYPE=text size=12 maxlength=16 name=upassword value="<?=$ur->password?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Name:</td>
<td colspan=3><INPUT TYPE=text size=30 maxlength=128 name=uname value="<?=$ur->name?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>E-mail:</td>
<td colspan=3><INPUT TYPE=text size=30 maxlength=128 name=uemail value="<?=$ur->email?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Max. allowed article status:</td>
<td colspan=3><Select name=umastat><?
	print_options($in_artstat,$ur->maxartstatus);
?></select></td>
</tr>
<tr>
<td>Status:</td>
<td colspan=3><select name=ustatus><? 
	print_options($in_userstat,$ur->status);
?></select></td>
</tr>
<tr>
<td colspan=2><input type=button class=button value="save" onClick="if(document.userf.uusername.value.length>0 && document.userf.upassword.value.length>0) document.userf.submit(); else alert('Empty Username and/or Password!');"></td>
<td><input type=button class=button value="cancel"  onClick="history.back();"></td>
<td align=right><? if($uid>1) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) location.href='user_delete.php?uid=<?=$uid?>';"><? } ?></td>
</tr>
</table>

</form>
</BODY>
</HTML>