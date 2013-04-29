<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

	$style_over="this.style.backgroundColor='003366';";
	$style_out="if(nsel!=this.id) this.style.backgroundColor='336699';";
	$style_start="border: 1px solid #FFFFFF; border-bottom: 0px; border-right: 0px; background-color: 336699";
?>
<HTML>
<HEAD>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Administration</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript>
<!--
	var nsel='';
	
	function setSel(n) {
		if(nsel!='') if(document.all[nsel]) document.all[nsel].style.backgroundColor='336699';
		nsel=n;
	}
// -->
</SCRIPT>
</HEAD>

<BODY topmargin=0 leftmargin=0 marginwidth=0 marginheight=0>
<!-- NAVIGATION MENU START -->
<table border=0 cellspacing=0 cellpadding=2 width=100% height=26>
<tr>
<td align=left class=yellow><img src=cms_logo2.gif align=absmiddle>&nbsp;&nbsp;<b>Management Console</b></td>
<td align=right class=yellow>Logged in as: <i><?=$ses_username?></i>&nbsp;-&nbsp;<a href=logout.php>[log off]</a>&nbsp;</td>
</tr>
</table>
<table border=0 cellspacing=0 cellpadding=2 width=100% height=40>
<tr bgcolor=#FFFFFF>
<td width=9% align=center id=td0 onClick="setSel('td0');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=categs.php target=main>Nodes</a></td>
<td width=9% align=center id=td1 onClick="setSel('td1');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=articles.php target=main>Articles</a></td>
<td width=9% align=center id=td9 onClick="setSel('td9');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=keywords.php target=main>Keywords</a></td>
<td width=9% align=center id=td2 onClick="setSel('td2');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=media.php target=main>Media</a></td>
<td width=9% align=center id=td3 onClick="setSel('td3');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=files.php target=main>Files</a></td>

<td width=9% align=center id=td11 onClick="setSel('td11');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=galleries.php target=main>Photo Gallery</a></td>
<td width=9% align=center id=td4 onClick="setSel('td4');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=templates.php target=main>Templates</a></td>
<td width=9% align=center id=td5 onClick="setSel('td5');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=ads.php target=main>Ads</a></td>
<td width=9% align=center id=td7 onClick="setSel('td7');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=members.php target=main>Members</a></td>
<td width=9% align=center id=td10 onClick="setSel('td10');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=mailers.php target=main>Newsletter</a></td>
<td width=9% align=center id=td6 onClick="setSel('td6');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=users.php target=main>Users</a></td>
<td width=9% align=center id=td8 onClick="setSel('td8');" style="<?=$style_start?>" onMouseOver="<?=$style_over?>" onMouseOut="<?=$style_out?>"><a href=config.php target=main>Config</a></td>

</tr>
</table>
<!-- NAVIGATION MENU END -->
</BODY>
</HTML>