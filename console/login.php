<?
	require_once("../common/globals.php");
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/usergroup.php");

	begin_console_session();
	
	$valid=0;
	$ses_userid=-1;
	
	if(isset($uname) && isset($pword) && $dbconn->connected) {
		$ur=new UserDBRecord($dbconn);
		if($ur->auth($uname,$pword)) {
			$valid=1; 
			$ses_userid=$ur->user_id;
			$ses_username=$ur->username;
			$ses_groupid=$ur->group_id;
			$ses_maxartstat=$ur->maxartstatus;
						
			$gr=new UsergroupDBRecord($dbconn);
			$gr->group_id=$ur->group_id;
			$gr->get();
			$ses_userperms=$gr->perms;
			$ses_startcat=$gr->startcat;

		}
		$dbconn->close();
	}
	
	
	if($valid)	header ("Location: frameset.php");
	else 		header ("Location: force_login.html");
?>