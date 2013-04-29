<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(10,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/keywords.php");
	require_once("../classes/locale.php");
	
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);
	
	$kl=new KeywordsList();
	$kl->populate($dbconn);

?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Config</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<table border=0 width=100%>
<tr>
<td valign=top>



</td>
<td valign=top>

<h2>Keywords :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(10,$BIT_ADD)) { ?><a href=key_form.php?kid=0>[add new...]</a><? } ?></h2>
<?
	if(count($kl->list)>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Word</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<count($kl->list); $i++) {
		if($i==0 || $kl->list[$i]->locale!=$kl->list[$i-1]->locale) echo "<tr><td colspan=2><b>".$locl->getName($kl->list[$i]->locale)."</b></td></tr>\n";
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$kl->list[$i]->keyword?></td>
	<td><? if(is_allowed(10,$BIT_MOD)) { ?><a href=key_form.php?kid=<?=$kl->list[$i]->key_id?>>[edit...]</a><? } ?></td>
	</tr>
<?
	}
	if(count($kl->list)>0) echo "</table>\n";
?>

</td>
</tr>
</table>
</BODY>
</HTML>