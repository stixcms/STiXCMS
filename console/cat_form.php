<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");	

 	if(!is_allowed(1,$BIT_MOD) && $id>0) SendError("Not allowed!");
 	if(!is_allowed(1,$BIT_ADD) && $id==0) SendError("Not allowed!");

 	require_once("../common/connect.php");
	require_once("../classes/category.php");
	require_once("../classes/locale.php");
	require_once("../classes/template.php");
	require_once("../classes/attachment.php");
			
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);
	
	if($id>0) {
		$cr=new CategoryDBRecord($dbconn);
		$cr->get($id); 
	} else {
		$cr=new CategoryRecord();
		$cr->cat_param=$param;
		$cr->parent_id=$nid;
	}

 	if($LOCK_FIRST_LEVEL && $id>0 && $cr->parent_id==0 && $cr->cat_param==1) SendError("Not allowed!");
 		
	$cl=new CategoryMap($dbconn);
	$startcat=$cl->getName($cr->parent_id+0);
	if($startcat=="") $startcat="&lt;top of the tree&gt;";	
	
	
	$sl=new TemplateList();
	$sl->populate($dbconn,$ses_userid,$ses_groupid);
	
	$att=new AttachmentList();
		

?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify Category Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>

<SCRIPT Language=Javascript>
<!--
	var conf=false;
	
	function ask4templ() {
		if(conf) return;
		if(confirm('Apply this template to all children nodes?')) {
			document.userf.cascadetempl.value='1';
			alert('Changes will apply when Node is saved.');
			conf=true;
		}
	}
	
	function doSubmit() {
<?	if($LOCK_FIRST_LEVEL && $cr->cat_param==1) {	?>
		if(parseInt(document.userf.ustartcat.value)==0) alert('First level of nodes is locked!'); else document.userf.submit();
<?	} else {	?>
		document.userf.submit();
<?	}	?>		
	}
// -->
</SCRIPT>

</HEAD>
<BODY>


<form name=userf action=cat_save.php method=post>
<input type=hidden name=cid value="<?=$id?>">
<input type=hidden name=cparam value="<?=$cr->cat_param?>">
<input type=hidden name=ccreated value="<?=$cr->createdate?>">
<input type=hidden name=ccreateu value="<?=$cr->createdby?>">
<input type=hidden name=cascadetempl value="0">

<table border=0 cellpadding=2 cellspacing=0 class=tab>
<tr>
<th colspan=3><?	if($id>0) {	?>
Modify node : <?=$cr->name?>
<?	} else {	?>
Add new node :
<?	}	?></td>
</tr>
<tr>
<td>Put under:</td>
<td colspan=2><input type=hidden name=ustartcat value="<?=$cr->parent_id+0?>"><input type=text size=20 disabled name=dummyname value="<?=$startcat?>"> <a href=# onClick="window.open('cat_select.php?treeparam=<?=$param?>','tree','width=610,height=450');">[change...]</a></td>
</tr>
<?
	for($i=0; $i<$lcount; $i++) {	
	
		$clr=new CatLocDBRecord($dbconn);
		$clr->cat_id=$cr->cat_id;
		$clr->locale=$locl->list[$i]->locale;
		$clr->get();	
			
		if($cr->cat_param==1 || $locl->list[$i]->is_default) {
?>
<tr>
<td>Name (<?=$locl->list[$i]->name?><? if($locl->list[$i]->is_default) echo " - default"; ?>):</td>
<td colspan=2><input type=text name=clname[<?=$locl->list[$i]->locale?>] size=40 maxlength=128 value="<?=$clr->name?>" onChange=check4quotes(this);></td>
</tr>	
<?			if($cr->cat_param==1) {	?>
<tr>
<td>Template:</td>
<td colspan=2><select name=ctemp[<?=$locl->list[$i]->locale?>] onChange=ask4templ();><? 
				for($j=0; $j<count($sl->list); $j++) {
					echo "<option value=".$sl->list[$j]->temp_id;
					if($sl->list[$j]->temp_id==$clr->temp_id) echo " selected";
					echo ">".$sl->list[$j]->title."</option>";
				}
?></select></td>
					<? if ($id>0){
					
					$att->cat_id=$id;
					 
					$att->populateCategPhotos($dbconn,$id,$locl->list[$i]->locale,$ATTYPE_MEDIA,1,1);
					$total_relmed=count($att->list);	
					 
						?>
					<tr>
					<td>Related media for category:</td>
						<td  valign=top>
						<a href="Javascript:window.open('att_cat_select.php?rt=<?=$ATTYPE_MEDIA?>&pid=<?=$id?>&lid=<?=$locl->list[$i]->locale?>','NEWPHOTO','width=600,height=400,scrollbars=yes,menubar=no,alwaysRaised=yes');void(0);">[Add new...]</a>
					<?	for($mi=0; $mi<$total_relmed; $mi++) {	?>
						 <br><a href="#" onClick="window.open('<?=$IN_MEDIA_URL.$att->list[$mi]->URI?>','PHOTO','width=<?=$att->list[$mi]->width?>,height=<?=$att->list[$mi]->height?>,menubar=no,scrollbars=yes,alwaysRaised=yes')"><?=$att->list[$mi]->URI?></a>&nbsp;&nbsp;&nbsp;<a href=# onclick=window.open('detach_photo.php?cid=<?=$id?>&lid=<?=$locl->list[$i]->locale?>&attid=<?=$att->list[$mi]->att_id?>','','menubar=0,scrollbars=0')>[Detach..]</a>
					<?	}	?>
						</td>
					 
					</tr>
					<?		}//end if id>0	
				} else {	?>
<input type=hidden name=ctemp[<?=$locl->list[$i]->locale?>] value="0">
<?			}
		}
	}
?>
<tr>
<td>Order:</td>
<td colspan=2><input type=text name=corder size=3 maxlength=4 value="<?=$cr->cat_order?>" onChange=check4quotes(this);></td>
</tr>
<?	if($cr->cat_param==1) {	?>
<tr>
<td>Status:</td>
<td colspan=2><select name=cstatus><? 
		print_options($in_catstat,$cr->status);
?></select></td>
</tr>
<?	} else {	?>
<input type=hidden name=cstatus value="I">
<?	}	?>
<tr>
<td><input type=button class=button value="save" onClick="doSubmit();"></td>
<td><input type=button class=button value="cancel"  onClick="location.href='categs.php?treeparam=<?=$param?>';"></td>
<td align=right><? if($id>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) location.href='cat_delete.php?id=<?=$id?>'; else return false;"><? } ?></td>
</tr>
</table>
</form>


<? if($id>0) showRecProps($cr->createdate,$cr->lastupdated); ?>


</BODY>
</HTML>