<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(2,$BIT_MOD)) SendError("Not allowed!");
	
		
	require_once("../common/connect.php");
	require_once("../classes/articles.php");
	
	$ar=new ArticleDBRecord($dbconn);
	$ar->art_id=$aid;
	
	for($i=0; $i<count($kw); $i++) {
		$ar->attach_key($kw[$i]);
	}
	
?>
<HTML>
<HEAD>	
<TITLE>Modify Article</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>keywords has been attached successfully!</h1>
	<BR><BR>
	<a href=# onClick="l=opener.location.href;
		if(l.substr(l.length-1,1)=='#') l=l.substr(0,l.length-1);
		opener.location.href=l;
		self.close();">close this window</a>.
</CENTER>

</BODY>
</HTML>