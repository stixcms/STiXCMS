<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");
	
	if($nid<=0) SendError("Cannot add an article here!");

	if(!is_allowed(11,$BIT_ADD) && $aid<=0) SendError("Not allowed!");
 	if(!is_allowed(11,$BIT_MOD) && $aid>0) SendError("Not allowed!");
 		
	require_once("../common/connect.php");
	require_once("../classes/gallery.php");
	require_once("../classes/attachment.php");
		
	$art=new GalleryDBRecord($dbconn);
	if($aid>0) {
		$art->gal_id=$aid;
		$art->get();
		$att=new AttachmentDBRecord($dbconn);
		
		$glist=new GalleryList();
		$glist->byid=$aid;
		$glist->getRelAtts($dbconn);
		$total=count($glist->list);
		
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
<TITLE>Gallery Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY !onLoad=switch2html();>

<form name=artf action=gal_save.php method=post>
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
<td>Title:</td>
<td colspan=3><input type=text <?=$readonly?> name=atitle size=50 class=form value="<?=$art->title?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Photos Per Line:</td>
<td><input type=text <?=$readonly?> name=photoperline size=10 class=form value="<?=$art->photos_per_line?>" ></td>
<td>Photos Per Page:</td>
<td ><input type=text <?=$readonly?> name=photoperpage size=10 class=form value="<?=$art->photos_per_page?>" ></td>
</tr>
<tr>
<td>Thumbnail height:</td>
<td><input type=text <?=$readonly?> name=thumbheight size=10 class=form value="<?=$art->thumbnail_height?>" onChange=check4quotes(this);></td>
<td>Thumbnail width:</td>
<td><input type=text <?=$readonly?> name=thumbwidth size=10 class=form value="<?=$art->thumbnail_width?>" onChange=check4quotes(this);></td>
</tr>

<tr>
<td>Status:</td>
	
<td><select class=form <?=$readonly?> name=astatus>
<option value=""></option>
<?print_options($in_galstat,$art->status)?>
	</select></td>


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
	<th colspan=7>Photos</th>
	</tr>
	<tr>
	<td>  Tumbnail</td><td >Photo</td><td>Text</td><td >&nbsp;</td >
	</tr>
	<?	for($i=0; $i<$total; $i++) {	?>
	<tr>
	<td><li><a href="#" onClick="window.open('<?=$IN_MEDIA_URL.$att->getURI($glist->list[$i]->att_id1)?>','viewatt','width=800,height=600,menubar=0,scrollbars=0')"> <?=$att->getTitle($glist->list[$i]->att_id1)?> </a> </td>
			<td><a href="#" onClick="window.open('<?=$IN_MEDIA_URL.$att->getURI($glist->list[$i]->att_id2)?>','viewatt','width=800,height=600,menubar=0,scrollbars=0')"> <?=$att->getTitle($glist->list[$i]->att_id2)?> </a> </td>
            <td><?=$glist->list[$i]->atext?></td>
            <td><? if($readonly=="") { ?><a href=gal_detach.php?gattid=<?=$glist->list[$i]->galatt_id?>&gid=<?=$glist->list[$i]->gal_id?>&nid=<?=$nid?>>[detach]</a><? } ?></li></td>
</tr>
<?	}	?>
	</td>	
	</tr>
	<tr>
	<td valign=bottom>
	
	
	
	<? if($readonly=="") { ?><!--a href=# onClick="Javascript:window.open('gal_select.php?attid=<?=$glist->list[$i]->galatt_id?>&obj=att2','attsel','width=610,height=450');">[add related articles ...]</a--><? } ?>
	</td>
	</tr>
	</table>
<form name=artf2 action=gal_attach.php method=post>
<input type=hidden name=gal_id value="<?=$art->gal_id?>">
<input type=hidden name=galattid value="<?=$art->galatt_id?>">
<input type=hidden name=att2 value="">
<input type=hidden  name=att1 value="">
<input type=hidden name=nid value="<?=$nid?>">
<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th >Add photos</th>
</tr>
<tr>
<td>Thumbnail:</td>
<td>
<input type=text readonly name=dummyatt1 size=10 value="">
<a href="#" onClick="window.open('gatt_select.php?rt=<?=$ATTYPE_MEDIA?>&pid=<?=$attid?>&obj=att1','','width=610,height=450');">Add/change</a>

</td>

<td>&nbsp;</td>
<td> Photo:</td>
<td >
<input type=text readonly name=dummyatt2 size=10 value="">
<a href=# onClick="Javascript:window.open('gatt_select.php?rt=<?=$ATTYPE_MEDIA?>&pid=<?=$attid?>&obj=att2','','width=610,height=450');">Add/change</a>

</td>
<td>&nbsp;</td>
<td>order:</td>
<td><input type=text  name=aorder size=10 class=form value="<?=$art->attorder?>" onChange=check4quotes(this);></td>
<td>&nbsp;</td>
<td>text:</td>
<td><input type=text  name=atxt size=10 class=form value="<?=$art->atext?>" onChange=check4quotes(this);></td>
<td><input type=button name=attach value=attach OnCLick="if ((document.artf2.att1.value!='') && (document.artf2.att2.value!='')) document.artf2.submit();else alert('you must attach some photos first');"></td>

</tr>
</table>
</form>

<form><input type=button class=button value="PREVIEW ARTICLE" onClick="doPreview();"></form>

<?	}	?>


<SCRIPT Language=Javascript>
<!--
	
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