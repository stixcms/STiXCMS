<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(10,$BIT_MOD) && $kid>0) SendError("Not allowed!");
 	if(!is_allowed(10,$BIT_ADD) && $kid<=0) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/keywords.php");
	require_once("../classes/locale.php");
	
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);
		
	$kr=new KeywordsDBRecord($dbconn);
	$kr->key_id=$kid;
	if($kid>0) $kr->get();

?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Keyword Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>

<form name=userf action=key_save.php method=post>
<input type=hidden name=kid value="<?=$kid?>">
<input type=hidden name=ccreated value="<?=$kr->createdate?>">
<input type=hidden name=ccreateu value="<?=$kr->createdby?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Keyword details form&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<tr>
<td>Locale:</td>
<td colspan=3><select <?=$readonly?> name=kloc class=form><?
	for($i=0; $i<$lcount; $i++) {
		echo "<option value=".$locl->list[$i]->locale;
		if($locl->list[$i]->locale==$kr->locale) echo " selected";
		echo ">".$locl->list[$i]->name."</option>";
	}
?></select></td>
</tr>
<tr>
<td>Word:</td>
<td colspan=3><INPUT TYPE=text <?=$readonly?> size=20 maxlength=80 name=kword value="<?=$kr->keyword?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td colspan=2><input type=button class=button value="save" onClick="if(document.userf.kword.value.length>0) document.userf.submit(); else alert('Empty word!');"></td>
<td><input type=button class=button value="cancel"  onClick="history.back();"></td>
<td align=right><? if($kid>1) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) location.href='key_delete.php?kid=<?=$kid?>';"><? } ?></td>
</tr>
</table>

</form>

<? if($kid>0) showRecProps($kr->createdate,$kr->lastupdated); ?>

</BODY>
</HTML>