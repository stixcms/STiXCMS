<?
	require_once("../common/globals.php");

	begin_console_session();	
	if($ses_userid<=0) header("Location: force_login.html");	

 	if(!is_allowed(1,$BIT_MOD) && $id>0) SendError("Not allowed!");
 	if(!is_allowed(1,$BIT_ADD) && $id==0) SendError("Not allowed!");
 			
	require_once("../common/connect.php");
	require_once("../classes/category.php");
	
	$cr=new CategoryDBRecord($dbconn);
	
	$cr->cat_id=$cid;
	$cr->parent_id=$ustartcat;
	$cr->cat_param=$cparam;
	$cr->cat_order=$corder;
	$cr->status=$cstatus;
	$cr->createdate=($cid>0)?$ccreated:time();
	$cr->createdby=($cid>0)?$ccreateu:$ses_userid;
	$cr->lastupdated=time();
	$cr->lastupdatedby=$ses_userid;
	
	if($cid==0) $success=$cr->insert(); else $success=$cr->update();
	
	if($success) {
		$cr->deleteLoc();
		$larr=array_keys($clname);
		for($i=0; $i<count($larr); $i++) {
			$clr=new CatLocDBRecord($dbconn);
			$clr->cat_id=$cr->cat_id;
			$clr->locale=$larr[$i];
			$clr->name=$clname[$larr[$i]];
			$clr->temp_id=$ctemp[$larr[$i]]+0;
			$clr->insert();
		}
		
		if($cascadetempl) {
			$cm=new CategoryMap($dbconn,"","",$cparam);	
			
			function cascadeTemplates($id,$p) {
				global $cm,$clname,$ctemp,$dbconn;
				$sub=$cm->getSubNodes($id,$p);
				for($j=0; $j<count($sub); $j++) {
					
					$larr=array_keys($clname);
					for($i=0; $i<count($larr); $i++) {
						$clr=new CatLocDBRecord($dbconn);
						$clr->cat_id=$sub[$j]->cat_id;
						
						$clr->locale=$larr[$i];
						$clr->get();
						$clr->temp_id=$ctemp[$larr[$i]]+0;
						$clr->update();
					}
					cascadeTemplates($sub[$j]->cat_id,$p);
				}
			}
			cascadeTemplates($cr->cat_id,$cr->cat_param);
		}
	}
	
	if(!$success) SendError("Unable to save category!"); else 
?>
<HTML>
<HEAD>	
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-7">
<TITLE>Modify Category Form</TITLE>
<?=getStyleSheet()?>
</HEAD>
<BODY>

<CENTER>
	<h1>Node <?=$cname?> has been saved!</h1>
	<BR><BR>
	<a href="cat_form.php?id=<?=$cr->cat_id?>&param=<?=$cparam?>">[return to form]</a>
	<BR><BR>
	<a href="categs.php?treeparam=<?=$cparam?>">[return to nodes]</a>	
</CENTER>

</BODY>
</HTML>