<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(8,$BIT_MOD)) SendError("Not allowed!");

	 		
	require_once("../common/connect.php");
	require_once("../classes/member.php");
	
	$ur=new MemberDBRecord($dbconn);
	$ur->member_id=$mid;
		
	$success=$ur->delete();
	
	if(!$success) SendError("Unable to remove member record!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify member</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>Member has been deleted!</h1>
		<br>
		<a href=members.php>[return to members]</a>
	</CENTER>
</BODY>
</HTML>