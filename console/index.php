<?
	require_once("../common/globals.php");
	
?>	
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Login</TITLE>
<?=getStyleSheet()?>
</HEAD>

<BODY onLoad=document.lf.uname.focus()>
<center>
<img src=cms_logo.gif>
<h1>Console Login</h1>
<form action=login.php method=post name=lf>
<table border=0 class=tab>
<tr>
<td>username:</td> 
<td><input type=text size=12 maxlength=16 name=uname onFocus="this.style.backgroundColor='66CCFF';" onBlur="this.style.backgroundColor='FFFFFF';"></td>
</tr>
<tr>
<td>password:</td> 
<td><input type=password size=12 maxlength=16 name=pword onFocus="this.style.backgroundColor='66CCFF';" onBlur="this.style.backgroundColor='FFFFFF';"></td>
</tr>
</table>
<br>
<input type=button value="enter >" class=button onClick="if(document.lf.uname.value.length>0) document.lf.submit();">
</form>
<br><br><br><br><br><br>
This is <b>workflow v3.1</b> made by <a href="http://www.steficon.com/" target="_blank">Steficon S.A.</a>
</center>
</BODY>
</HTML>
