<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

	if(!is_allowed(11,$BIT_MOD)) SendError("Not allowed!");
		
	require_once("../common/connect.php");
	require_once("../classes/member.php");
	require_once("../classes/mailqueue.php");
	
	$emails=Array();
	
	function putinarray($ml) {
		global $emails;
		$l=count($emails);
		for($i=0; $i<count($ml); $i++) $emails[$l++]=$ml[$i]->email;
	}
	
	if($rcomma!="") $emails=explode(",",$rcomma);
	else if($rstand>0) {
		$sl=new MemberList();
		$sl->only_4mail="1";
		$sl->by_status="A";
		$sl->populate($dbconn);
		putinarray($sl->list);
	}	
	
	if(count($emails)<=0) SendError("No recipients!"); 
	else {
		for($i=0; $i<count($emails); $i++) {
			$mqr=new mailqueueDBRecord($dbconn);
			$mqr->mailer_id=$mid;
			$mqr->email=$emails[$i];
			$mqr->issent="N";
			$mqr->insert();
		}	
		
		header("Location: mailer_processq.php?mid=".$mid);
	}
?>