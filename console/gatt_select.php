<?
	require_once("../common/globals.php");

	begin_console_session();
		
	if($ses_userid<=0) header("Location: force_login.html");
	
 	if(!is_allowed(3,$BIT_READ)) SendError("Not allowed!");	
?>
<HTML>
<SCRIPT Language=Javascript>
<!--
	var pid;
	var rt;
	
	pid=<?=$pid+0?>;
	rt=<?=$rt?>;

	function doClose() {
		l=opener.location.href;
		if(l.substr(l.length-1,1)=='#') l=l.substr(0,l.length-1);
		opener.location.href=l;
		self.close();	
	}	
// -->
</SCRIPT>

<TITLE>Selector</TITLE>

<FRAMESET cols="180,*" border=0 frameborder=0>
<FRAME NAME=tree SRC="gatt_tree.php?typ=<?=$rt?>&insertit=<?=$insertit?>&obj=<?=$obj?>">
<FRAME NAME=list SRC="gatt_list.php?nid=0&typ=<?=$rt?>&insertit=<?=$insertit?>&obj=<?=$obj?>">
</FRAMESET>

<BODY>

</BODY>
</HTML>
