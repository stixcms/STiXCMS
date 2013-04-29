<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

	if(!is_allowed(9,$BIT_ADD) && $mid<=0) SendError("Not allowed!");
 	if(!is_allowed(9,$BIT_MOD) && $mid>0) SendError("Not allowed!");
		
	require_once("../common/connect.php");
	require_once("../classes/module.php");
	require_once("../classes/locale.php");
	
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);
	
	$med=new ModuleDBRecord($dbconn);
	if($mid>0) {
		$med->mod_id=$mid;
		$med->get();
	} else {
		$med->OwnerUserID=$ses_userid;	
		$med->OwnerGroupID=$ses_groupid;
		$med->RowPerms=$DEFAULT_MASK;		
	}

	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,1)) SendError("Not allowed!");	
	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) $readonly="disabled"; else $readonly="";	
		
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Module</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript1.2 SRC="../common/common.js"></SCRIPT>
</HEAD>
<BODY>

<form name=medf action=mod_save.php method=post ENCTYPE="multipart/form-data">
<input type=hidden name=nid value="<?=$nid?>">
<input type=hidden name=mid value="<?=$mid?>">
<input type=hidden name=ccreated value="<?=$med->createdate?>">
<input type=hidden name=ccreateu value="<?=$med->createdby?>">
<input type=hidden name=ouid value="<?=$med->OwnerUserID?>">
<input type=hidden name=ogid value="<?=$med->OwnerGroupID?>">
<input type=hidden name=rperms value="<?=$med->RowPerms?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Module details form</td>
</tr>
<tr>
<td>Locale:</td>
<td colspan=3><select <?=$readonly?> name=mloc class=form><?
	for($i=0; $i<$lcount; $i++) {
		echo "<option value=".$locl->list[$i]->locale;
		if($locl->list[$i]->locale==$med->locale) echo " selected";
		echo ">".$locl->list[$i]->name."</option>";
	}
?></select></td>
</tr>
<tr>
<td>File:</td>
<td colspan=3>
<?
	if($mid>0 && $med->URI!="") {
		echo "<a href=\"".$IN_MODULE_URL.$med->URI."\" class=link target=viewwin>[εμφάνιση ...]</a> - Νέο αρχείο: ";
		echo " <input type=hidden name=muri value=\"".$med->URI."\">";
	} 
?>
<INPUT TYPE=hidden name=MAX_FILE_SIZE value=1000000><input type=file <?=$readonly?> name=mfile class=form></td>
</tr>
<tr>
<td>Title:</td>
<td colspan=3><input type=text <?=$readonly?> name=mtitle size=30 value="<?=$med->title?>" onChange=check4quotes(this); class=form></td>
</tr>
<tr>
<td>Position:</td>
<td colspan=3><select name=mplace <?=$readonly?> class=form><? 
	print_options($in_modplace,$med->place);
?></select></td>
</tr>
<tr>
<td>Status:</td>
<td colspan=3><select name=mstatus <?=$readonly?> class=form><? 
	print_options($in_modstat,$med->status);
?></select></td>
</tr>
<tr>
<td>Order:</td>
<td colspan=3><input type=text <?=$readonly?> name=morder size=3 maxlength=4 value="<?=$med->modorder?>" onChange=check4quotes(this); class=form></td>
</tr>
<?	if($readonly=="") {	?>
<tr>
<td>Security:</td>
<td colspan=3><a href=Javascript:openPermWindow('medf');>[edit settings ...]</a></td>
</tr>
<?	}	?>
<tr>
<td colspan=2><? if(allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) { ?><input type=button class=button value="save" onClick="if(document.medf.mfile.value.length>0 || parseInt(document.medf.mid.value)>0) document.medf.submit(); else alert('Empty File!');"><? } ?></td>
<td><input type=button class=button value="cancel" onClick="history.back();"></td>
<td align=right><? if(allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,4)) { if($mid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) { document.medf.action='mod_delete.php'; document.medf.submit(); }"><? } } ?></td>
</tr>
</table>

</form>

<? if($mid>0) showRecProps($med->createdate,$med->lastupdated); ?>
</BODY>
</HTML>