<?
	require_once("../common/globals.php");
	
	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");

 	if(!is_allowed(10,$BIT_READ)) SendError("Not allowed!");
 	
	require_once("../common/connect.php");
	require_once("../classes/users.php");
	require_once("../classes/keywords.php");
	require_once("../classes/locale.php");

		
	$locl=new LocaleList();
	$locl->populate($dbconn);
	$lcount=count($locl->list);

	$al=new KeywordsList();    
	$al->loc=$loc;
	$al->populate($dbconn,$ses_userid,$ses_groupid);
	$key_count=count($al->list);
	
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Articles</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>


<h2>Keywords (<?=$locl->getName($loc)?>)</h2>
<form action=attach_key.php method=post>
<input type=hidden name=aid value="<?=$pid?>">

Select one or more keywords (by holding down Ctrl button):<br>

<select name=kw[] multiple size=20>
<?	for($i=0; $i<$key_count; $i++) {	?>	
<option value="<?=$al->list[$i]->key_id?>"><?=$al->list[$i]->keyword?></option>
<?	}	?>
</select>

<input type=submit class=button value="attach">
</form>

</BODY>
</HTML>