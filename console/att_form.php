<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

	if(!is_allowed($typ+1,$BIT_ADD) && $aid<=0) SendError("Not allowed!");
 	if(!is_allowed($typ+1,$BIT_MOD) && $aid>0) SendError("Not allowed!");
		
	require_once("../common/connect.php");
	require_once("../classes/attachment.php");

	
	$med=new AttachmentDBRecord($dbconn);
	if($aid>0) {
		$med->att_id=$aid;
		$med->get();
	} else {
		$med->OwnerUserID=$ses_userid;	
		$med->OwnerGroupID=$ses_groupid;
		$med->RowPerms=$DEFAULT_MASK;		
		$med->typ=$typ;
	}

	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,1)) SendError("Not allowed!");	
	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) $readonly="disabled"; else $readonly="";	
		
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Media</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript1.2 SRC="../common/common.js"></SCRIPT>
</HEAD>
<BODY>

<form name=medf action=att_save.php method=post ENCTYPE="multipart/form-data">
<input type=hidden name=nid value="<?=$nid?>">
<input type=hidden name=aid value="<?=$aid?>">
<input type=hidden name=mmime value="<?=$med->mime?>">
<input type=hidden name=mtyp value="<?=$med->typ?>">
<input type=hidden name=mfilesize value="<?=$med->filesize?>">
<input type=hidden name=ccreated value="<?=$med->createdate?>">
<input type=hidden name=ccreateu value="<?=$med->createdby?>">
<input type=hidden name=ouid value="<?=$med->OwnerUserID?>">
<input type=hidden name=ogid value="<?=$med->OwnerGroupID?>">
<input type=hidden name=rperms value="<?=$med->RowPerms?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Media/File details form</td>
</tr>
<tr>
<td>File:</td>
<td colspan=3>
<?
	if($aid>0 && $med->URI!="") {
		echo "<a href=\"".$IN_MEDIA_URL.$med->URI."\" class=link target=viewwin>[view ...]</a> - Upload new file: ";
		echo " <input type=hidden name=muri value=\"".$med->URI."\">";
	} 
?>
<INPUT TYPE=hidden name=MAX_FILE_SIZE value=50000000><input type=file <?=$readonly?> name=mfile class=form onChange="document.medf.mwidth.value='';document.medf.mheight.value='';"></td>
</tr>
<tr>
<td>Title:</td>
<td colspan=3><input type=text <?=$readonly?> name=mtitle size=50 value="<?=$med->title?>" onChange=check4quotes(this); class=form></td>
</tr>
<? 	if($typ!=$ATTYPE_FILES) {	?>
<tr>
<td>Width:</td>
<td><input type=text <?=$readonly?> name=mwidth size=3 maxlength=3 value="<?=$med->width?>" onChange=check4quotes(this); class=form>pixels</td>
<td align=right>Height:</td>
<td><input type=text <?=$readonly?> name=mheight size=3 maxlength=3 value="<?=$med->height?>" onChange=check4quotes(this); class=form>pixels</td>
</tr>
<?	} else {	?>
<input type=hidden name=mwidth value="<?=$med->width?>">
<input type=hidden name=mheight value="<?=$med->height?>">
<?	}	?>
<?	if($med->filesize>0) {	?>
<tr>
<td>File size:</td>
<td colspan=3><?=$med->filesize?> bytes</td>
</tr>
<?	}	?>
<tr>
<td>Status:</td>
<td><select name=mstatus <?=$readonly?> class=form><? 
	print_options($in_attstat,$med->status);
?></select></td>
<td align=right>Order:</td>
<td><input type=text <?=$readonly?> name=morder size=3 maxlength=4 value="<?=$med->attorder?>" onChange=check4quotes(this); class=form></td>
</tr>
<?	if($readonly=="") {	?>
<!--tr>
<td>Create thumbnail? <input type=checkbox name=createThumb value=1></td>
<td colspan=3><input type=text size=4 maxlength=2 name=thumbsize>% smaller</td>
</tr-->
<tr>
<td>Security:</td>
<td colspan=3><a href=Javascript:openPermWindow('medf');>[edit settings ...]</a></td>
</tr>
<?	}	?>
<tr>
<td colspan=2><? if(allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) { ?><input type=button class=button value="save" onClick="if(document.medf.mfile.value.length>0 || parseInt(document.medf.aid.value)>0) document.medf.submit(); else alert('Empty File!');"><? } ?></td>
<td><input type=button class=button value="cancel" onClick="history.back();"></td>
<td align=right><? if(allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,4)) { if($aid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) { document.medf.action='att_delete.php'; document.medf.submit(); }"><? } } ?></td>
</tr>
</table>

</form>

<? if($aid>0) showRecProps($med->createdate,$med->lastupdated); ?>

</BODY>
</HTML>