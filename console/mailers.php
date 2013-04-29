<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(11,$BIT_READ)) SendError("Not allowed!");

	require_once("../common/connect.php");
	require_once("../classes/mailer.php");

	$ml=new MailerList();    
	$ml->populate($dbconn,$ses_userid,$ses_groupid);
	$mail_count=count($ml->list);
	
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Mailers</TITLE>
<?=getStyleSheet()?>

</HEAD>
<BODY>


<h2>Newsletters&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? if(is_allowed(11,$BIT_ADD)) { ?><a href=mailer_form.php?mid=0>[add new...]</a><? } ?></h2>
<?
	if($mail_count>0) echo "<table border=1 cellspacing=0 cellpadding=1><tr><th>Subject</th><th>Last update</th><th>&nbsp;</th></tr>\n";
	for($i=0; $i<$mail_count; $i++) {
?>	
	<tr id=tr<?=$i?> onMouseOver="this.style.backgroundColor='666699';" onMouseOut="this.style.backgroundColor=body.style.backgroundColor;">
	<td><?=$ml->list[$i]->subject?> <a href="mailer_form.php?mid=<?=$ml->list[$i]->mailer_id?>"> <? if(is_allowed(11,$BIT_MOD)) { ?>[edit...]</a><? } ?></td>
	<td><?=timestamp2date($ml->list[$i]->lastupdated)?></td>
	<td><? if(is_allowed(11,$BIT_MOD)) { ?><a href="mailer_send.php?mid=<?=$ml->list[$i]->mailer_id?>">[send...]</a><? } ?></td>
	</tr>
<?
	}
	if($mail_count>0) echo "</table>\n";
?>


</BODY>
</HTML>