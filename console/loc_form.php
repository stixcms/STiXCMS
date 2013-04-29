<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(6,$BIT_MOD) && $lid>0) SendError("Not allowed!");
 	if(!is_allowed(6,$BIT_ADD) && $lid<=0) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/locale.php");
	
	$lr=new LocaleDBRecord($dbconn);
	$lr->locale=$lid;
	if($lid>0) $lr->get();

?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Locale Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>

<form name=userf action=loc_save.php method=post>
<input type=hidden name=lid value="<?=$lid?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Locale details form</td>
</tr>
<tr>
<td>Name:</td>
<td colspan=3><INPUT TYPE=text size=20 maxlength=32 name=lname value="<?=$lr->name?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>ISO code:</td>
<td colspan=3><INPUT TYPE=text size=16 maxlength=16 name=liso value="<?=$lr->iso?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td colspan=2><input type=button class=button value="save" onClick="if(document.userf.lname.value.length>0 && document.userf.liso.value.length>0) document.userf.submit(); else alert('Empty Name and/or ISO code!');"></td>
<td><input type=button class=button value="cancel"  onClick="history.back();"></td>
<td align=right><? if($lid>1) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure? This will delete all information related to this Locale.')) location.href='loc_delete.php?lid=<?=$lid?>';"><? } ?></td>
</tr>
</table>

</form>
</BODY>
</HTML>