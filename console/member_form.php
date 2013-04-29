<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(8,$BIT_ADD) && $mid<=0) SendError("Not allowed!");
 	if(!is_allowed(8,$BIT_MOD) && $mid>0) SendError("Not allowed!");
 		
 	require_once("../common/connect.php");
	require_once("../classes/member.php");
	
	$sr=new MemberDBRecord($dbconn);
	$sr->member_id=$mid;
	if($mid>0) $sr->get(); else $sr->date_created=time();
	

?>
<HTML>
<HEAD>	
<TITLE>Modify Member Form</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript SRC=../common/common.js></SCRIPT>
</HEAD>
<BODY>


<form name=modnodef action=member_save.php method=post>
<input type=hidden name=mid value="<?=$mid?>">
<input type=hidden name=sdatecreated value="<?=$sr->date_created?>">


<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=2>Member details form</td>
</tr>
<tr>
<td>First name:</td>
<td><INPUT TYPE=text size=30 maxlength=80 name=fname value="<?=$sr->name?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Last name:</td>
<td><INPUT TYPE=text size=30 maxlength=50 name=lname value="<?=$sr->lastname?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Username:</td>
<td><input type=text name=susern size=16 maxlength=16 value="<?=$sr->username?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Password:</td>
<td><input type=text name=spass size=16 maxlength=16 value="<?=$sr->password?>"></td>
</tr>
<tr>
<td>E-mail:</td>
<td><input type=text name=semail size=30 maxlength=128 value="<?=$sr->email?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Address:</td>
<td><input type=text name=saddr size=40 value="<?=$sr->address?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Postcode:</td>
<td><input type=text name=szip size=6 maxlength=10 value="<?=$sr->zip?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>City:</td>
<td><input type=text name=scity size=30 maxlength=50 value="<?=$sr->city?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Country:</td>
<td><input type=text name=scountry size=30 maxlength=50 value="<?=$sr->country?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Phone:</td>
<td><input type=text name=sphone size=20 maxlength=50 value="<?=$sr->phone?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Fax:</td>
<td><input type=text name=sfax size=20 maxlength=50 value="<?=$sr->fax?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Company:</td>
<td><input type=text name=scomp size=30 maxlength=50 value="<?=$sr->company?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>TAX number:</td>
<td><input type=text name=safm size=10 maxlength=10 value="<?=$sr->afm?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>TAX place:</td>
<td><input type=text name=sdoy size=20 maxlength=30 value="<?=$sr->doy?>" onChange=check4quotes(this);></td>
</tr>
<tr>
<td>Mailing list participation:</td>
<td><select name=smail><? 
	print_options($in_mlstat,$sr->mailinglist);
?></select></td>
</tr>
<tr>
<td>Status:</td>
<td><select name=sstatus><? 
	print_options($in_memstat,$sr->status);
?></select></td>
</tr>
<?	if($mid>0) {	?>
<tr>
<td colspan=2>Account created at <?=timestamp2date($sr->date_created)?> . Last modified at <?=timestamp2date($sr->date_updated)?>.</td>
</tr>
<?	}	?>
<tr>
<td><input type=button class=button value="save" onClick="if(document.modnodef.fname.value.length>0) document.modnodef.submit(); else alert('Empty name!');"></td>
<td align=right><input type=button class=button value="cancel"  onClick="location.href='members.php';">
<? if($mid>0) { ?><input type=button class=button value="delete" onClick="if(confirm('Are you sure?')) location.href='member_delete.php?mid=<?=$mid?>';"><? } ?></td>
</tr>
</table>


</form>

</BODY>
</HTML>