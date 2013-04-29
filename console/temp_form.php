<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if(!is_allowed(5,$BIT_ADD) && $tid<=0) SendError("Not allowed!");
 	if(!is_allowed(5,$BIT_MOD) && $tid>0) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/template.php");
	
	
	$tr=new TemplateDBRecord($dbconn);
	if($tid>0) {
		$tr->temp_id=$tid;
		$tr->get();
		
	} else {
		$tr->OwnerUserID=$ses_userid;	
		$tr->OwnerGroupID=$ses_groupid;
		$tr->RowPerms=$DEFAULT_MASK;		
	}

	
	if(!allowed2do($tr->OwnerUserID,$tr->OwnerGroupID,$tr->RowPerms,1)) SendError("Not allowed!");	
	if(!allowed2do($tr->OwnerUserID,$tr->OwnerGroupID,$tr->RowPerms,2)) $readonly="disabled"; else $readonly="";	
	

?>
<HTML>
<HEAD>	
<TITLE>Template Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>

<form name=adf action=temp_save.php method=post>
<input type=hidden name=tid value="<?=$tid?>">
<input type=hidden name=ccreated value="<?=$tr->createdate?>">
<input type=hidden name=ccreateu value="<?=$tr->createdby?>">
<input type=hidden name=ouid value="<?=$tr->OwnerUserID?>">
<input type=hidden name=ogid value="<?=$tr->OwnerGroupID?>">
<input type=hidden name=rperms value="<?=$tr->RowPerms?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Template details form</td>
</tr>
<tr>
<td>Title:</td>
<td colspan=3><input type=text <?=$readonly?> name=atitle size=30 class=form value="<?=$tr->title?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>HTML:</td>
<td colspan=3><textarea <?=$readonly?> name=ahtml class=form cols=140 rows=25><?=$tr->html?></textarea>
<input type=button class=button value="preview" onClick="doPreview(document.adf.ahtml.value);">
</td>
</tr>
<tr>
<td>Status:</td>
<td><select class=form <?=$readonly?> name=astatus><? 
	print_options($in_tempstat,$art->status);
?></select>
</td>
</tr>
<?	if($readonly=="") {	?>
<tr>
<td>Security:</td>
<td colspan=3><a href=Javascript:openPermWindow('adf');>[edit settings ...]</a></td>
</tr>
<?	}	?>
<tr>
<td colspan=2><? if(allowed2do($tr->OwnerUserID,$tr->OwnerGroupID,$tr->RowPerms,2)) { ?><input type=button class=button value="save" onClick="doSubmit();"><? } ?></td>
<td><input type=button class=button value="cancel" onClick="history.back();"></td>
<td align=right><? if(allowed2do($tr->OwnerUserID,$tr->OwnerGroupID,$tr->RowPerms,4)) { if($tid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) { document.adf.action='temp_delete.php'; document.adf.submit(); }"><? } } ?></td>
</tr>
</table>
</form>

<a href=# onClick="window.open('taghelp.php','taghelp','width=610,height=460,scrollbars=yes');">Help on available Tags...</a>

<SCRIPT Language=Javascript>
<!--

	function doSubmit() {
		if(document.adf.atitle.value.length>0) document.adf.submit(); else alert('Empty title!');
	}
	
	function doPreview(s) {
		w=window.open('','pwin');
		w.document.write(s);
	}
	
// -->
</SCRIPT>

<? if($tid>0) showRecProps($tr->createdate,$tr->lastupdated); ?>

</BODY>
</HTML>