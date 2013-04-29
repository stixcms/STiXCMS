<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/usergroup.php");

	$unl=new UsernameList();
	$unl->populate($dbconn,$ses_userid,$ses_groupid);

	$ugl=new UsergroupList();
	$ugl->populate($dbconn);
?>
<HTML>
<HEAD>	
<TITLE>Security</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>


<form name=fsec>
<table border=0 cellpadding=2 cellspacing=0 class=tab>
<tr>
<th colspan=2>Record security settings</td>
</tr>
<tr>
<td>Owner user:</td>
<td><select name=user><?
	for($i=0; $i<count($unl->users); $i++) {
		echo "<option value=".$unl->users[$i]->user_id;
		if($unl->users[$i]->user_id==$ouid) echo " selected";
		echo ">".$unl->users[$i]->username."</option>";
	}
?></select></td>
</tr>
<tr>
<td>Owner group:</td>
<td><select name=group><?
	for($i=0; $i<count($ugl->list); $i++) {
		echo "<option value=".$ugl->list[$i]->group_id;
		if($ugl->list[$i]->group_id==$ogid) echo " selected";
		echo ">".$ugl->list[$i]->name."</option>";
	}
?></select></td>
</tr>
<tr>
<td>User props:</td>
<td><input type=checkbox name=rp11 value=1 <?=has_perm($rp,0,1)?"checked":""?>>Read<input type=checkbox name=rp12 value=2 <?=has_perm($rp,0,2)?"checked":""?>>Modify<input type=checkbox name=rp13 value=4 <?=has_perm($rp,0,4)?"checked":""?>>Delete</td>
</tr>
<tr>
<td>Group props:</td>
<td><input type=checkbox name=rp21 value=1 <?=has_perm($rp,1,1)?"checked":""?>>Read<input type=checkbox name=rp22 value=2 <?=has_perm($rp,1,2)?"checked":""?>>Modify<input type=checkbox name=rp23 value=4 <?=has_perm($rp,1,4)?"checked":""?>>Delete</td>
</tr>
<tr>
<td>Everybody props:</td>
<td><input type=checkbox name=rp31 value=1 <?=has_perm($rp,2,1)?"checked":""?>>Read<input type=checkbox name=rp32 value=2 <?=has_perm($rp,2,2)?"checked":""?>>Modify<input type=checkbox name=rp33 value=4 <?=has_perm($rp,2,4)?"checked":""?>>Delete</td>
</tr>
<tr>
<td colspan=2><input type=button class=button value="apply" onClick=goSubmit();> <input type=button class=button value="cancel" onClick="opener.focus(); self.close();"></td>
</tr>
</table>
</form>

</BODY>
<SCRIPT Language=Javascript>
<!--
	function goSubmit() {
		f=document.fsec;
		opener.document.<?=$formname?>.ouid.value=f.user.options[f.user.selectedIndex].value;
		opener.document.<?=$formname?>.ogid.value=f.group.options[f.group.selectedIndex].value;
		rp1=parseInt(f.rp11.value*f.rp11.checked)+parseInt(f.rp12.value*f.rp12.checked)+parseInt(f.rp13.value*f.rp13.checked);
		rp2=parseInt(f.rp21.value*f.rp21.checked)+parseInt(f.rp22.value*f.rp22.checked)+parseInt(f.rp23.value*f.rp23.checked);
		rp3=parseInt(f.rp31.value*f.rp31.checked)+parseInt(f.rp32.value*f.rp32.checked)+parseInt(f.rp33.value*f.rp33.checked);
		opener.document.<?=$formname?>.rperms.value=rp1+""+rp2+""+rp3;
		opener.focus();
		self.close();
	}
// -->
</SCRIPT>
</HTML>