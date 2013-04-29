<?
	require_once("../common/connect.php");
	require_once("../classes/category.php");
	
	$sm=new CategoryMap($dbconn);

		
	function print_tree($id,$param,$history="") {
		global $sm,$exp,$level,$calling_location;
		if($history!="") $h=explode(",",$history);
		echo "<table border=0 cellspacing=1 cellpadding=0>\n";
		$t=$sm->getSubNodes($id,$param);
		if(count($t)>0) $level++;
		for($i=0; $i<count($t); $i++) {
			echo "<tr><td>".str_repeat("&nbsp;&nbsp;",$level-1)."<a href=".$calling_location;
			for($j=0; $j<count($h); $j++) if($h[$j]!="") echo "exp[]=".$h[$j]."&";
			$img="<SPAN class=plus>+</SPAN>";
			if(isset($exp)) if(in_array($t[$i]->cat_id,$exp)) $img="<SPAN class=minus>-</SPAN>";
			echo "exp[]=".($t[$i]->cat_id)."&nid=".($t[$i]->cat_id)."&nparam=".$param.">".$img."</a>&nbsp;<a class=cat href=\"Javascript:handleCat(".($t[$i]->cat_id).",".$param.",'".$t[$i]->name."');\">".$t[$i]->name."</a>";
			if(isset($exp)) if(in_array($t[$i]->cat_id,$exp)) print_tree($t[$i]->cat_id,$param,$t[$i]->cat_id.",".$history); 
			echo "</td></tr>\n";
		}
		if(count($t)>0) $level--;
		echo "</table>\n";
	}


	if($expandall) for($i=0; $i<count($sm->nodes); $i++) $exp[$i]=$sm->nodes[$i]->cat_id;
 
	if($treeparam==1) {
		echo $sm->getName($ses_startcat);
		$sc=$ses_startcat;
	} else $sc=0;
	$level=0;
	print_tree($sc,2);

?>