<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if($nid<=0) SendError("Cannot add an ad here!");

	if(!is_allowed(7,$BIT_ADD) && $aid<=0) SendError("Not allowed!");
 	if(!is_allowed(7,$BIT_MOD) && $aid>0) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/ads.php");
	require_once("../classes/attachment.php");
	require_once("../classes/category.php");
	
	$sm=new CategoryMap($dbconn);
	
	$adr=new AdsDBRecord($dbconn);
	if($aid>0) {
		$adr->ad_id=$aid;
		$adr->get();
		
		$cats=$adr->getCatArray();
	} else {
		$adr->hlink="http://";
		$adr->OwnerUserID=$ses_userid;	
		$adr->OwnerGroupID=$ses_groupid;
		$adr->RowPerms=$DEFAULT_MASK;		
	}

	$atl=new AttachmentList();
	$atl->by_status="A";
	$atl->populate($dbconn,$ATTYPE_MEDIA,$ses_userid,$ses_groupid);
	$total_att=count($atl->list);
	
	if(!allowed2do($adr->OwnerUserID,$adr->OwnerGroupID,$adr->RowPerms,1)) SendError("Not allowed!");	
	if(!allowed2do($adr->OwnerUserID,$adr->OwnerGroupID,$adr->RowPerms,2)) $readonly="disabled"; else $readonly="";	
	

	function print_tree($id,$param,$history="") {
		global $sm,$exp,$level,$readonly,$cats;
		if($history!="") $h=explode(",",$history);
		echo "<table border=0 cellspacing=1 cellpadding=0>\n";
		$t=$sm->getSubNodes($id,$param);
		if(count($t)>0) $level++;
		for($i=0; $i<count($t); $i++) {
			echo "<tr><td>".str_repeat("&nbsp;&nbsp;",$level-1)."<input type=checkbox ".$readonly." class=form name=cats[] value=".$t[$i]->cat_id;
			if($cats) if(in_array($t[$i]->cat_id,$cats)) echo " checked";
			echo ">&nbsp;";
			if($level>1) echo $t[$i]->name; else echo "<b>".$t[$i]->name."</b>";
			if(isset($exp)) if(in_array($t[$i]->cat_id,$exp)) print_tree($t[$i]->cat_id,$param,$t[$i]->cat_id.",".$history); 
			echo "</td></tr>\n";
		}
		if(count($t)>0) $level--;
		echo "</table>\n";
	}
	
	for($i=0; $i<count($sm->nodes); $i++) $exp[$i]=$sm->nodes[$i]->cat_id;
?>
<HTML>
<HEAD>	
<TITLE>Ad Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>

<form name=adf action=ad_save.php method=post>
<input type=hidden name=nid value="<?=$nid?>">
<input type=hidden name=aid value="<?=$aid?>">
<input type=hidden name=ccreated value="<?=$adr->createdate?>">
<input type=hidden name=ccreateu value="<?=$adr->createdby?>">
<input type=hidden name=ouid value="<?=$adr->OwnerUserID?>">
<input type=hidden name=ogid value="<?=$adr->OwnerGroupID?>">
<input type=hidden name=rperms value="<?=$adr->RowPerms?>">

<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Ad details form</td>
</tr>
<tr>
<td>Name:</td>
<td colspan=3><input type=text <?=$readonly?> name=aname size=30 maxlength=32 class=form value="<?=$adr->name?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Media:</td>
<td colspan=3><select <?=$readonly?> name=aattid size=1 class=form>
<option value=0>-</option>
<?
	for($i=0; $i<$total_att; $i++) {
		echo "<option value=".$atl->list[$i]->att_id;
		if($adr->att_id==$atl->list[$i]->att_id) echo " selected";
		echo ">".$atl->list[$i]->title."</option>\n";	
	}
?>
</select></td>
</tr>
<tr>
<td>Ad text:</td>
<td colspan=3><input type=text <?=$readonly?> name=atext class=form size=70 value="<?=$adr->adtext?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Hyperlink:</td>
<td colspan=3><input type=text <?=$readonly?> name=ahlink class=form size=30 value="<?=$adr->hlink?>"></td>
</tr>
<tr>
<td>Status:</td>
<td><select class=form <?=$readonly?> name=astatus><? 
	print_options($in_adstat,$art->status);
?></select>
</td>
<td align=right>Order:</td>
<td><input type=text <?=$readonly?> class=form name=aorder size=3 maxlength=4 value="<?=$adr->aorder?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td valign=top>Assignments:</td>
<td !align=right colspan=3 bgcolor=#66666F class=tab><?
	print_tree($ses_startcat,1);
?>

<div align=right><a href=# onClick="doCheck(true);">[check all]</a> - <a href=# onClick="doCheck(false);">[uncheck all]</a></div>
</td>
</tr>
<?	if($readonly=="") {	?>
<tr>
<td>Security:</td>
<td colspan=3><a href=Javascript:openPermWindow('adf');>[edit settings ...]</a></td>
</tr>
<?	}	?>
<tr>
<td colspan=2><? if(allowed2do($adr->OwnerUserID,$adr->OwnerGroupID,$adr->RowPerms,2)) { ?><input type=button class=button value="save" onClick="doSubmit();"><? } ?></td>
<td><input type=button class=button value="cancel" onClick="history.back();"></td>
<td align=right><? if(allowed2do($adr->OwnerUserID,$adr->OwnerGroupID,$adr->RowPerms,4)) { if($aid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) { document.adf.action='ad_delete.php'; document.adf.submit(); }"><? } } ?></td>
</tr>
</table>
</form>



<SCRIPT Language=Javascript>
<!--
	function doCheck(s) {
		f=document.adf;
		for(i=0; i<f.elements.length; i++) {
			if(f.elements[i].type=='checkbox') f.elements[i].status=s;
		}
	}
	function doSubmit() {
		if(document.adf.aattid.selectedIndex>0 && document.adf.atext.value.length>0) {
			alert('Cannot have both Media and Ad text in the ad!');
			return;
		}
		if(document.adf.aname.value.length>0) document.adf.submit(); else alert('Empty name!');
	}
	
// -->
</SCRIPT>

<? if($aid>0) showRecProps($adr->createdate,$adr->lastupdated); ?>

</BODY>
</HTML>