<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(1,$BIT_READ)) SendError("Not allowed!");
 	
 	
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Select category</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY onLoad=self.focus();>

<SCRIPT Language=Javascript>
<!--
	function handleCat(cid,p,nam) {
		opener.document.userf.ustartcat.value=cid;
		opener.document.userf.dummyname.value=nam;
		opener.focus();
		self.close();
	}
// -->
</SCRIPT>
<table border=0 width=100% height=100%>
<tr>
<td valign=top><h2>Select a node:</h2>
<form name=cf>
<a href=# onClick="handleCat(<?=$ses_startcat?>,1,'&lt;top of the tree&gt;');">&lt;top of the tree&gt;</a>
</form>
<?
	$calling_location="cat_select.php?treeparam=".$treeparam."&";
	require_once("tree.php");
?>

</td>
</tr>
<tr>
<td valign=bottom align=right><form><input type=button class=button value="cancel" onClick="opener.focus();self.close();"></form></td>
</tr>
</table>
</BODY>
</HTML>