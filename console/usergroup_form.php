<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_MOD) || !is_allowed(0,$BIT_ADD)) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/usergroup.php");
	require_once("../classes/category.php");
	
		
	$ur=new UsergroupDBRecord($dbconn);
	if($ugid>0) {
		$ur->group_id=$ugid; 
		$ur->get();
	}

	$sm=new CategoryMap($dbconn);
	$startcat=$sm->getName($ur->startcat);
	if($startcat=="") $startcat="&lt;top of the tree&gt;";
		
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>UserGroup Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>

<form name=userf action=usergroup_save.php method=post>
<input type=hidden name=ugid value="<?=$ugid?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>User Group details form</td>
</tr>
<tr>
<td>Name:</td>
<td colspan=3><INPUT TYPE=text size=30 maxlength=64 name=uname value="<?=$ur->name?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td valign=top>Permissions:</td>
<td colspan=3><table border=0>
<?

	for($i=0; $i<count($in_permcat); $i++) {
		echo "<tr><td>".$in_permcat[$i]." :</td><td>";
		echo "read<input type=checkbox name=uperm[".$i."][0] value=".$BIT_READ." ".(((0+substr($ur->perms,$i,1)) & $BIT_READ)?"checked>":">");
		echo "modify<input type=checkbox name=uperm[".$i."][1] value=".$BIT_MOD." ".(((0+substr($ur->perms,$i,1)) & $BIT_MOD)?"checked>":">");
		echo "add<input type=checkbox name=uperm[".$i."][2] value=".$BIT_ADD." ".(((0+substr($ur->perms,$i,1)) & $BIT_ADD)?"checked>":">");
		echo "</td></tr>\n";
	}
?>
</table></td>
</tr>
<tr>
<td>Start of category tree:</td>
<td colspan=3><input type=hidden name=ustartcat value="<?=$ur->startcat+0?>"><input type=text size=20 disabled name=dummyname value="<?=$startcat?>"> <a href=# onClick="window.open('cat_select.php?treeparam=1','tree','width=610,height=450');">[change...]</a></td>
</tr>
<tr>
<td colspan=2><input type=button class=button value="save" onClick="if(document.userf.uname.value.length>0) document.userf.submit(); else alert('Empty name!');"></td>
<td><input type=button class=button value="cancel"  onClick="history.back();"></td>
<td align=right><? if($ugid>1) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) location.href='usergroup_delete.php?ugid=<?=$ugid?>';"><? } ?></td>
</tr>
</table>

</form>
</BODY>
</HTML>