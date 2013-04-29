<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

	if(!is_allowed(11,$BIT_MOD)) SendError("Not allowed!");
			
	require_once("../common/connect.php");
	require_once("../classes/mailqueue.php");
	require_once("../classes/mailer.php");
		
	$MAILS_PER_TURN=10;

	$med=new MailerDBRecord($dbconn);
	$med->mailer_id=$mid;
	$med->get();

		
	$ml=new mailqueueList();
	$ml->UnsentOfMailer($dbconn,$mid,$MAILS_PER_TURN);
	$mcount=count($ml->list);
	
	if($mcount<=0) {
		echo "<HTML>";
		getStyleSheet();
		echo "Finished!"; 
		echo "</HTML>";
		exit();
	}
	for($i=0; $i<$mcount; $i++) {
		echo "Attemting to send mail to ".$ml->list[$i]->email." <br>\n";
		
		mail($ml->list[$i]->email,$med->subject,$med->body,"From: ".$med->fromaddr."\r\nContent-Type: text/html\r\n");
		
		$mqr=new mailqueueDBRecord($dbconn);
		$mqr->row_id=$ml->list[$i]->row_id;
		$mqr->mailer_id=$mid;
		$mqr->email=$ml->list[$i]->email;
		$mqr->issent="Y";
		$mqr->update();
	}
	
	$med->lastsent=time();
	$med->lastsentcount+=$mcount;
	$med->update();
?>
<HTML>
<BODY onLoad="location.href='mailer_processq.php?mid=<?=$mid?>';">
</BODY>
</HTML>