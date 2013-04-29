<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(6,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/locale.php");


	$ll=new LocaleList();
	$ll->populate($dbconn);

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

<h2>Locales :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(6,$BIT_ADD)) { ?><a href=loc_form.php?lid=0>[add new...]</a><? } ?></h2>
<?
	if(count($ll->list)>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>id</th><th>Name</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<count($ll->list); $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$ll->list[$i]->locale?></td>
	<td><?=$ll->list[$i]->name?></td>
	<td><? if(is_allowed(6,$BIT_MOD)) { ?><a href=loc_form.php?lid=<?=$ll->list[$i]->locale?>>[edit...]</a><? } ?></td>
	</tr>
<?
	}
	if(count($ll->list)>0) echo "</table>\n";
?>

</td>
</tr>
</table>
</BODY>
</HTML>