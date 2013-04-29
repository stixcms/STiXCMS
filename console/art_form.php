<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if($nid<=0) SendError("Cannot add an article here!");

	if(!is_allowed(2,$BIT_ADD) && $aid<=0) SendError("Not allowed!");
 	if(!is_allowed(2,$BIT_MOD) && $aid>0) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/articles.php");
	require_once("../classes/attachment.php");
	require_once("../classes/locale.php");
	require_once("../classes/keywords.php");
	
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);
		
	$art=new ArticleDBRecord($dbconn);
	if($aid>0) {
		$art->art_id=$aid;
		$art->get();
		
		$ral=new ArticleList();
		$ral->getRelatedArticles($dbconn,$aid,$ses_userid,$ses_groupid);
		$total_relart=count($ral->list);
		
		$rml=new AttachmentList();
		$rml->getRelatedAtt($dbconn,$aid,$ATTYPE_MEDIA,$ses_userid,$ses_groupid);
		$total_relmed=count($rml->list);

		$rfl=new AttachmentList();
		$rfl->getRelatedAtt($dbconn,$aid,$ATTYPE_FILES,$ses_userid,$ses_groupid);
		$total_relfil=count($rfl->list);

		$rkl=new KeywordsList();
		$rkl->ofArticle($dbconn,$aid);
		$total_relkey=count($rkl->list);
		
	} else {
		$art->OwnerUserID=$ses_userid;	
		$art->OwnerGroupID=$ses_groupid;
		$art->RowPerms=$DEFAULT_MASK;		
	}

	if(!allowed2do($art->OwnerUserID,$art->OwnerGroupID,$art->RowPerms,1)) SendError("Not allowed!");	
	if(!allowed2do($art->OwnerUserID,$art->OwnerGroupID,$art->RowPerms,2) || intval($art->status)>intval($ses_maxartstat)) $readonly="disabled"; else $readonly="";	
	
	function may_use_div() {
		global $HTTP_USER_AGENT;
		$usediv=false;
		if(($f=stristr($HTTP_USER_AGENT,"MSIE "))) {
			if( doubleval(substr($f,5,3)) >= 5.5 ) $usediv=true;
		}
		return $usediv;
	}	
?>
<HTML>
<HEAD>	
<TITLE>Article Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY !onLoad=switch2html();>

<form name=artf action=art_save.php method=post>
<input type=hidden name=nid value="<?=$nid?>">
<input type=hidden name=aid value="<?=$aid?>">
<input type=hidden name=ccreated value="<?=$art->createdate?>">
<input type=hidden name=ccreateu value="<?=$art->createdby?>">
<input type=hidden name=ouid value="<?=$art->OwnerUserID?>">
<input type=hidden name=ogid value="<?=$art->OwnerGroupID?>">
<input type=hidden name=rperms value="<?=$art->RowPerms?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Article details form</td>
</tr>
<tr>
<td>Locale:</td>
<td colspan=3><select class=form <?=$readonly?> name=aloc><? 
	for($i=0; $i<$lcount; $i++) {
		echo "<option value=".$locl->list[$i]->locale;
		if($locl->list[$i]->locale==$art->locale) echo " selected";
		echo ">".$locl->list[$i]->name."</option>";
	}
?></select></td>
</tr>
<tr>
<td>Title:</td>
<td colspan=3><input type=text <?=$readonly?> name=atitle size=50 class=form value="<?=$art->title?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Short Description:</td>
<td colspan=3><input type=text <?=$readonly?> name=ashortdesc class=form size=70 value="<?=$art->shortdesc?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Article body:</td>
<td colspan=3>
<input type=hidden name=afulltxt value="<?=htmlspecialchars($art->fulltxt,ENT_QUOTES)?>">
<?
	if(may_use_div()) echo "<span id=container unselectable=on contenteditable=false>";
?>
<textarea class=form <?=$readonly?> name=tcontent cols=70 rows=15><?=$art->fulltxt?></textarea>
<?
	if(may_use_div()) echo "<center><a href=Javascript:switch2html();>[Switch to wysiwyg editor]</a></center></span>";
?>
</td>
</tr>
<tr>
<td>Start date:</td>
<td><input type=text <?=$readonly?> name=astartdate size=16 maxlength=16 value="<?=timestamp2date($art->startdate)?>" onChange=checkdate(this);></td>
<td align=right>End date:</td>
<td><input type=text <?=$readonly?> name=aenddate size=16 maxlength=16 value="<?=timestamp2date($art->enddate)?>" onChange=checkdate(this);></td>
</tr>
<tr>
<td>Status:</td>
<? 
	if(intval($art->status)>intval($ses_maxartstat)) $max=count($in_artstat); else $max=intval($ses_maxartstat);
?>	
<td><select class=form <?=$readonly?> name=astatus><? 
	for($i=0; $i<=$max; $i++) {
		echo "<option value=".$i;
		if($i==intval($art->status)) echo " selected";
		echo ">".$in_artstat[$i]."</option>";
	}
?></select></td>
<td align=right>Order:</td>
<td><input type=text <?=$readonly?> class=form name=aorder size=3 maxlength=4 value="<?=$art->art_order?>" onChange=check4quotes(this);></td>
</tr>
<?	if($readonly=="") {	?>
<tr>
<td>Security:</td>
<td colspan=3><a href=Javascript:openPermWindow('artf');>[edit settings ...]</a></td>
</tr>
<?	}	?>
<tr>
<td colspan=2><? if(allowed2do($art->OwnerUserID,$art->OwnerGroupID,$art->RowPerms,2)) { ?><input type=button class=button value="save" onClick="doSubmit();"><? } ?></td>
<td><input type=button class=button value="cancel" onClick="history.back();"></td>
<td align=right><? if(allowed2do($art->OwnerUserID,$art->OwnerGroupID,$art->RowPerms,4)) { if($aid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) { document.artf.action='art_delete.php'; document.artf.submit(); }"><? } } ?></td>
</tr>
</table>
</form>

<?	if($aid>0) {	?>

	<table border=0 cellpadding=3 cellspacing=0 class=tab>
	<tr>
	<th colspan=7>Related material</th>
	</tr>
	<tr>
	<td valign=top>Related articles:<br>
<?	for($i=0; $i<$total_relart; $i++) {	?>
	<li><?=$ral->list[$i]->title?> <? if($readonly=="") { ?><a href=detach.php?nid=<?=$nid?>&pid=<?=$aid?>&rid=<?=$ral->list[$i]->art_id?>&rt=1>[detach]</a><? } ?></li>
<?	}	?>
	</td>
	<th width=1 rowspan=2><img src=../common/img/calend.gif width=1 height=1></t>	
	<td valign=top>Related media:<br>
<?	for($i=0; $i<$total_relmed; $i++) {	?>
	<li><?=$rml->list[$i]->title?> <? if($readonly=="") { ?><a href=detach.php?nid=<?=$nid?>&pid=<?=$aid?>&rid=<?=$rml->list[$i]->att_id?>&rt=<?=$ATTYPE_MEDIA?>>[detach]</a><? } ?></li>
<?	}	?>
	</td>
	<th width=1 rowspan=2><img src=../common/img/calend.gif width=1 height=1></t>
	<td valign=top>Related files:<br>
<?	for($i=0; $i<$total_relfil; $i++) {	?>
	<li><?=$rfl->list[$i]->title?> <? if($readonly=="") { ?><a href=detach.php?nid=<?=$nid?>&pid=<?=$aid?>&rid=<?=$rfl->list[$i]->att_id?>&rt=<?=$ATTYPE_FILES?>>[detach]</a><? } ?></li>
<?	}	?>
	</td>
	<th width=1 rowspan=2><img src=../common/img/calend.gif width=1 height=1></t>
	<td valign=top>Related keywords:<br>
<?	for($i=0; $i<$total_relkey; $i++) {	?>
	<li><?=$rkl->list[$i]->keyword?> <? if($readonly=="") { ?><a href=detach_key.php?nid=<?=$nid?>&pid=<?=$aid?>&rid=<?=$rkl->list[$i]->key_id?>>[detach]</a><? } ?></li>
<?	}	?>
	</td>	
	</tr>
	<tr>
	<td valign=top><? if($readonly=="") { ?><a href=# onClick="Javascript:window.open('art_select.php?rt=1&pid=<?=$aid?>','attsel','width=610,height=450');">[add related articles ...]</a><? } ?></td>
	<td valign=top><? if($readonly=="") { ?><a href=# onClick="Javascript:window.open('att_select.php?rt=<?=$ATTYPE_MEDIA?>&pid=<?=$aid?>','attsel','width=610,height=450');">[add related media ...]</a><? } ?></td>
	<td valign=top><? if($readonly=="") { ?><a href=# onClick="Javascript:window.open('att_select.php?rt=<?=$ATTYPE_FILES?>&pid=<?=$aid?>','attsel','width=610,height=450');">[add related files ...]</a><? } ?></td>
	<td valign=top><? if($readonly=="") { ?><a href=# onClick="Javascript:window.open('key_select.php?pid=<?=$aid?>&loc=<?=$art->locale?>','attsel','width=610,height=450');">[add related keywords ...]</a><? } ?></td>
	</tr>
	</table>

<form><input type=button class=button value="PREVIEW ARTICLE" onClick="doPreview();"></form>

<?	}	?>

<?
	if(may_use_div()) {

		$col[0]="000000";
		$col[1]="111111";
		$col[2]="222222";
		$col[3]="333333";
		$col[4]="444444";
		$col[5]="555555";
		$col[6]="666666";
		$col[7]="777777";
		$col[8]="888888";
		$col[9]="999999";
		$col[10]="AAAAAA";
		$col[11]="BBBBBB";
		$col[12]="CCCCCC";
		$col[13]="DDDDDD";
		$col[14]="EEEEEE";
		$col[15]="FFFFFF";
		$col[16]="550000";
		$col[17]="770000";
		$col[18]="990000";
		$col[19]="BB0000";
		$col[20]="DD0000";
		$col[21]="FF0000";
		$col[22]="555500";
		$col[23]="777700";
		$col[24]="999900";
		$col[25]="BBBB00";
		$col[26]="DDDD00";
		$col[27]="FFFF00";
		$col[28]="005500";
		$col[29]="007700";
		$col[30]="009900";
		$col[31]="00BB00";
		$col[32]="00DD00";
		$col[33]="00FF00";
		$col[34]="005555";
		$col[35]="007777";
		$col[36]="009999";
		$col[37]="00BBBB";
		$col[38]="00DDDD";
		$col[39]="00FFFF";
		$col[40]="000055";
		$col[41]="000077";
		$col[42]="000099";
		$col[43]="0000BB";
		$col[44]="0000DD";
		$col[45]="0000FF";
		$col[46]="550055";
		$col[47]="770077";
		$col[48]="990099";
		$col[49]="BB00BB";
		$col[50]="DD00DD";
		$col[51]="FF00FF";
		$col[52]="775500";
		$col[53]="997700";
		$col[54]="BB9900";
		$col[55]="DDBB00";
		$col[56]="FFDD00";
		$col[57]="990077";
		$col[58]="BB0099";
		$col[59]="FF00DD";		
		
		echo "<div id='colorpicker' style='position: absolute; top: 130; left: 150; width: 200; height: 120; z-index:100; visibility: hidden;'>\n";
		echo "<table border=0 cellspacing=1 cellpadding=0 bgcolor=#FFFFFF>\n";
		for($i=0; $i<3; $i++) {
			echo "<tr>\n";
			for($j=0; $j<20; $j++) {
				echo "<td width=10 height=10 bgcolor=#".$col[$i*20+$j]."><a class=link href=Javascript:pickcolor('".$col[$i*20+$j]."');><img src=../common/img/dot.gif width=10 height=10 border=0></a></td>\n";
			}
			echo "</tr>\n";			
		}
		echo "<tr><td colspan=20 align=right><a href=# class=link onClick=document.all.colorpicker.style.visibility='hidden';>[Cancel]</a></td></tr>\n";
		echo "</table>\n";
		echo "</div>\n";
	}
?>		


<SCRIPT Language=Javascript>
<!--
	var usediv=false;
	
<?	if(may_use_div()) {	?>

	usediv=true;
	function switch2html() {
		document.artf.afulltxt.value=document.artf.tcontent.value;
		document.all.container.innerHTML="<table border=0><tr><td><button unselectable='On' title='Cut' onclick=document.execCommand('Cut');hcontent.focus(); style='background-image:url(../common/icons/cut.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Copy' onclick=document.execCommand('Copy');hcontent.focus(); style='background-image:url(../common/icons/copy.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Paste' onclick=document.execCommand('Paste') style='background-image:url(../common/icons/paste.gif); width:21; height:21; border:none;'></button>"
						+"&nbsp;"
						+"<button unselectable='On' title='Bold' onclick=document.execCommand('Bold');hcontent.focus(); style='background-image:url(../common/icons/bold.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Italics' onclick=document.execCommand('Italic');hcontent.focus(); style='background-image:url(../common/icons/italic.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Underline' onclick=document.execCommand('Underline');hcontent.focus(); style='background-image:url(../common/icons/under.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='StrikeThrough' onclick=document.execCommand('StrikeThrough');hcontent.focus(); style='background-image:url(../common/icons/strike.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='SuperScript' onclick=document.execCommand('SuperScript');hcontent.focus(); style='background-image:url(../common/icons/super.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='SubScript' onclick=document.execCommand('SubScript');hcontent.focus(); style='background-image:url(../common/icons/sub.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Foreground colour' onclick=document.all.colorpicker.style.visibility='visible'; style='background-image:url(../common/icons/forecolor.gif); width:21; height:21; border:none;'></button>"
						+"<select onChange=document.execCommand('FontSize',false,this.options[this.selectedIndex].value);hcontent.focus();><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option><option value=7>7</option></select>"
						+"&nbsp;"
						+"<button unselectable='On' title='InsertOrderedList' onclick=document.execCommand('InsertOrderedList');hcontent.focus(); style='background-image:url(../common/icons/ol.gif); width:21; height:21; border:none;'></button>"						
						+"<button unselectable='On' title='InsertUnorderedList' onclick=document.execCommand('InsertUnorderedList');hcontent.focus(); style='background-image:url(../common/icons/ul.gif); width:21; height:21; border:none;'></button>"						
						+"&nbsp;"
						+"<button unselectable='On' title='Left Justify' onclick=document.execCommand('JustifyLeft');hcontent.focus(); style='background-image:url(../common/icons/left.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Right Justify' onclick=document.execCommand('JustifyRight');hcontent.focus(); style='background-image:url(../common/icons/right.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Center Justify' onclick=document.execCommand('JustifyCenter');hcontent.focus(); style='background-image:url(../common/icons/center.gif); width:21; height:21; border:none;'></button>"
						+"&nbsp;"
						+"<button unselectable='On' title='Outdent' onclick=document.execCommand('Outdent');hcontent.focus(); style='background-image:url(../common/icons/outdent.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Indent' onclick=document.execCommand('Indent');hcontent.focus(); style='background-image:url(../common/icons/indent.gif); width:21; height:21; border:none;'></button>"
						+"&nbsp;"
						+"<button unselectable='On' title='Create link' onclick=document.execCommand('CreateLink',true);hcontent.focus(); style='background-image:url(../common/icons/url.gif); width:21; height:21; border:none;'></button>"
						+"<button unselectable='On' title='Insert image' onclick=doInsertImage();hcontent.focus(); style='background-image:url(../common/icons/image.gif); width:21; height:21; border:none;'></button>"
						+"&nbsp;</td></tr><tr><td><div id=hcontent contenteditable<? if($readonly!="") echo "=false"; ?> align=left class=divedit></div><center><a href=Javascript:switch2text(); class=link>[switch to simple editor]</a></center></td></tr></table>";
		document.all.hcontent.innerHTML=document.artf.afulltxt.value;
	}

	function doInsertImage() {
		window.open('att_select.php?rt=<?=$ATTYPE_MEDIA?>&insertit=true','attsel','width=610,height=450');			
	}
	function doInsertImageAction(uri) {
		document.all.hcontent.innerHTML+='<IMG SRC=<?=$IN_MEDIA_URL?>'+uri+'>';
	}
		
	function switch2text() {
		document.artf.afulltxt.value=document.all.hcontent.innerHTML;
		document.all.container.innerHTML="<textarea name=tcontent <?=$readonly?> cols=70 rows=15></textarea><center><a href=Javascript:switch2html(); class=link>[switch to wysiwyg editor]</a></center>";
		document.artf.tcontent.value=document.artf.afulltxt.value;
	}
	
	function pickcolor(c) {
		document.execCommand('ForeColor',false,c);
		hcontent.focus();
		document.all.colorpicker.style.visibility='hidden';
	}
	
<?	}	?>

	function doSubmit() {
		if(document.artf.tcontent) document.artf.afulltxt.value=document.artf.tcontent.value;
		else if(document.all.hcontent) document.artf.afulltxt.value=document.all.hcontent.innerHTML;
		if(document.artf.atitle.value.length>0) document.artf.submit(); else alert('Empty title!');
	}
	
	function doPreview() {
		nw=window.open('../site/content.php?artid=<?=$aid?>','preview','width=780,height=570,scrollbars=yes');	
		nw.focus();
	}
// -->
</SCRIPT>

<? if($aid>0) showRecProps($art->createdate,$art->lastupdated); ?>

</BODY>
</HTML>