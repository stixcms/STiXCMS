<?
	require_once("../common/globals.php");

	begin_console_session();
		
	if($ses_userid<=0) header("Location: force_login.html");
	
 	if(!is_allowed(4,$BIT_READ)) SendError("Not allowed!");	
?>
<HTML>

<FRAMESET cols="180,*" border=0 frameborder=0>
<FRAME NAME=tree SRC="files_tree.php" >
<FRAME NAME=list SRC="files_list.php?nid=0">
</FRAMESET>
</HTML>
