<?
	require_once("../common/globals.php");

	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(0,$BIT_MOD) || !is_allowed(0,$BIT_ADD)) SendError("Not allowed!");


	require_once("../common/connect.php");
	require_once("../classes/usergroup.php");
	
	$ur=new UsergroupDBRecord($dbconn);
	
	$ur->group_id=$ugid;	
	$ur->name=$uname;
	$ur->perms="";
	for($i=0; $i<count($in_permcat); $i++) {
		$bits=0;
		for($j=0; $j<$NoOfBits; $j++) $bits+=$uperm[$i][$j];
		$ur->perms.=$bits;
	}
	$ur->startcat=$ustartcat;
		
	if($ugid<=0) $success=$ur->insert(); else $success=$ur->update();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify UserGroup</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>User group has been saved!</h1>
		<br>
		<a href=users.php>[return to user management]</a>
	</CENTER>
</BODY>
</HTML>