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
		parent.list.location.href='att_list.php?nid='+cid+'&nname='+nam+'&insertit=<?=$insertit?>';
	}
// -->
</SCRIPT>

<?
	$treeparam=$typ;
	$calling_location="att_tree.php?treeparam=".$typ."&typ=".$treeparam."&insertit=".$insertit."&";
	require_once("tree.php");
?>