<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

	if(!is_allowed(11,$BIT_MOD)) SendError("Not allowed!");
		
	require_once("../common/connect.php");
	require_once("../classes/mailer.php");

	
	$med=new MailerDBRecord($dbconn);
	if($mid>0) {
		$med->mailer_id=$mid;
		$med->get();
	}

	if(!allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,1) || !allowed2do($med->OwnerUserID,$med->OwnerGroupID,$med->RowPerms,2)) SendError("Not allowed!");	
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Mailer</TITLE>
<?=getStyleSheet()?>
<SCRIPT Language=Javascript1.2 SRC="../common/common.js"></SCRIPT>
</HEAD>
<BODY>

<form name=medf action=mailer_send2.php method=post>
<input type=hidden name=mid value="<?=$mid?>">


<table border=0 cellspacing=0 cellpadding=2 class=tab>
<tr>
<th colspan=4>Newsletter Send form</td>
</tr>
<tr>
<td>Subject:</td>
<td colspan=3><?=$med->subject?></td>
</tr>
<tr>
<td>Date created:</td>
<td colspan=3><?=timestamp2date($med->createdate)?></td>
</tr>
<tr>
<td>Date last updated:</td>
<td colspan=3><?=timestamp2date($med->lastupdated)?></td>
</tr>
<tr>
<td>Last sent on:</td>
<td colspan=3><?=timestamp2date($med->lastsent)?></td>
</tr>
<tr>
<td>Sent count:</td>
<td colspan=3><?=$med->lastsentcount+0?></td>
</tr>
<tr>
<td colspan=4>&nbsp;</td>
</tr>
<tr>
<td align=right>Send to:</td>
<td colspan=3><select name=rstand>
<option value=0></option>
<option value=1>All Members</option>
</select></td>
</tr>
<tr>
<td align=right valign=top>or enter comma delimited list of e-mail addresses:</td>
<td colspan=3><textarea name=rcomma cols=50 rows=10></textarea></td>
</tr>

<tr>
<td colspan=2><input type=submit class=button value="send"></td>
<td colspan=2><input type=button class=button value="cancel" onClick="location.href='mailers.php';"></td>
</tr>
</table>

</form>


</BODY>
</HTML>