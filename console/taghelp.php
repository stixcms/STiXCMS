<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
?>	
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Tag help</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
<table border=0>
<tr>
<td valign=top>
	<h2>TAGS</h1>
	
	<h1>[%CHARSET%]</h1>
	Is replaced by the equivalent META tag for the current character set.
	<br>
	
	<h1>[%CONTENT%]</h1>
	CSS classes: art_title,art_body,art_summary<br>
	Is replaced by the content (Articles,Modules)
	<br>
	
	<h1>[%ADS%] , [%ADS_IN(n)%]</h1>
	[%ADS%] is replaced by all the ads of the node<br>
	[%ADS_IN(n)%] is replaced by the n-th Ad of the node (n=0,1,...)
	<br>
	
	<h1>[%MENU%]</h1>
	CSS classes: node_on,node_off,a.node:*,node_table<br>
	Is replaced by the navigation menu of Nodes
	<br>
	
	<h1>[%MENU2%]</h1>
	Is replaced by the navigation menu of Nodes, using a special Javascript structure
	<br>
		
	<h1>[%PATH%]</h1>
	CSS classes: a.path<br>
	Is replaced by the path of nodes
	<br>
	
	<h1>[%RELATED_FILES%]</h1>
	Is replaced by a list of related files of focused article
	<br>
		
	<h1>[%RELATED_MEDIA%] , [%RELATED_MEDIA_IN(n)%]</h1>
	[%RELATED_MEDIA%] is replaced by a list of related media of focused article<br>
	[%RELATED_MEDIA_IN(n)%] displays inline the n-th related media of the focused article (n=0,1,...)
	<br>
	
	<h1>[%RELATED_ARTICLES%]</h1>
	Is replaced by a list of related articles of focused article
	<br>
		
	<h1>[%SEARCHFORM%]</h1>
	exposes document.searchform object<br>
	Is replaced by a simple search form.
	<br>
		
	<h1>[%TICKER%]</h1>
	Under construction
	<br>	
</td>
</tr>
</table>

</BODY>
</HTML>