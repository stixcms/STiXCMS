<?
	require_once("../common/globals.php");
	
	begin_console_session();
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(8,$BIT_ADD) && $mid<=0) SendError("Not allowed!");
 	if(!is_allowed(8,$BIT_MOD) && $mid>0) SendError("Not allowed!");

	 		
	require_once("../common/connect.php");
	require_once("../classes/member.php");
	
	$ur=new MemberDBRecord($dbconn);
	
	$ur->member_id=$mid;
	$ur->username=$susern;
	$ur->password=$spass;
	$ur->name=$fname;
	$ur->lastname=$lname;
	$ur->email=$semail;
	$ur->status=$sstatus;
        $ur->address=$saddr;
        $ur->zip=$szip;
        $ur->city=$scity;
        $ur->country=$scountry;
        $ur->phone=$sphone;
        $ur->fax=$sfax;
        $ur->company=$scomp;
        $ur->afm=$safm;
        $ur->doy=$sdoy;
        $ur->mailinglist=$smail;
        $ur->date_created=$sdatecreated;
        $ur->date_updated=time();
			
	if($mid<=0) $success=$ur->insert(); else $success=$ur->update();
	
	if(!$success) SendError("DB error!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify Member</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>
	<CENTER>
		<h1>Member details have been saved!</h1>
		<br>
		<a href=members.php>[return to members]</a>
	</CENTER>
</BODY>
</HTML>