<?
	require_once("../common/globals.php");

	begin_console_session();
		
	if($ses_userid<=0) header("Location: force_login.html");
	
 	if(!is_allowed(2,$BIT_READ)) SendError("Not allowed!");	
?>
<HTML>

<FRAMESET cols="180,*" border=0 frameborder=0>
<FRAME NAME=tree SRC="Gal_tree.php" >
<FRAME NAME=list SRC="Gal_list.php?nid=<?=$ses_startcat?>">
</FRAMESET>


</HTML>