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
<BODY>

<SCRIPT Language=Javascript>
<!--
	function handleCat(cid,p,nam) {
		if(top.location.href.indexOf('art_select.php')>0) scr='art_list2.php'; else scr='art_list.php';
		parent.list.location.href=scr+'?nid='+cid+'&nname='+nam;
	}
// -->
</SCRIPT>

<?
	$treeparam=1;
	$calling_location="art_tree.php?treeparam=1&";
	require_once("tree.php");
?>