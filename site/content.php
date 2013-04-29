<?
	require_once("../common/globals.php");
	require_once("../common/connect.php");
	require_once("../classes/category.php");
	require_once("../classes/articles.php");
	require_once("../classes/attachment.php");
	require_once("../classes/module.php");
	require_once("../classes/locale.php");
	require_once("../classes/ads.php");
	require_once("../classes/template.php");
	
	start_site_session();
	
	// create sitemap
	$cm=new CategoryMap($dbconn,"I","<>",1,$ses_locale);
	
	// no parameters, it's home
	if(!isset($sel) && !isset($artid)) $sel=$cm->getFirstNode();	
	
	// get Articles
	$al=new ArticleList();
	if(isset($artid)) {
		$ar=new ArticleDBRecord($dbconn);
		$ar->art_id=$artid;
		$ar->get();
		$sel=$ar->getCategory();
		$al->list[0]=$ar;
	} else {
		$al->by_status="3"; // only Published articles
		$al->status_comp=">=";
		$al->full_info=true;
		$al->only_active_today=true; // get non-expired articles
		$al->by_locale=$ses_locale;
		if($query!="") {
			$al->search($dbconn,$query);
			if(count($al->list)==0) $al->searchfull($dbconn,$query);
		} else {
			$al->getArticlesOfNode($dbconn,intval($sel),1,1);
		}
	}
	$art_count=count($al->list);

	// get Node info and see if Public or Private
	$nr=new CategoryDBRecord($dbconn);
	$nr->get($sel);
	if($nr->status=="P" && $mem_id<=0) {
//		header("Location: member_login.php?artid=".$artid."&sel=".$sel);
//		exit();	
		if (!isset($PHP_AUTH_USER)) {
			header('WWW-Authenticate: Basic realm="Site auth"');
			header('HTTP/1.0 401 Unauthorized');
			echo 'You have to authenticate to see this page!\n';
			exit();
		} else {
			include_once("../classes/member.php");
			$mr=new MemberDBRecord($dbconn);
			$mr->auth($PHP_AUTH_USER,$PHP_AUTH_PW);
			if($mr->member_id>0) {
				$mem_id=$mr->member_id;
				$mem_username=$mr->username;
			} else {
				header('WWW-Authenticate: Basic realm="Site auth"');
				header('HTTP/1.0 401 Unauthorized');
				echo 'You have to authenticate to see this page!\n';
				exit();
			}
		}
	}
	
	// get related material
	if($art_count==1) {
		$ral=new ArticleList();
		$ral->by_status="3"; // only Published articles
		$ral->status_comp=">=";
		$ral->full_info=true;
		$ral->only_active_today=true; // get non-expired articles
		$ral->getRelatedArticles($dbconn,$al->list[0]->art_id,1,1);
		$total_relart=count($ral->list);
				
		$rml=new AttachmentList();
		$rml->by_status="A";
		$rml->getRelatedAtt($dbconn,$al->list[0]->art_id,$ATTYPE_MEDIA,1,1);
		$total_relmed=count($rml->list);

		$rfl=new AttachmentList();
		$rfl->by_status="A";
		$rfl->getRelatedAtt($dbconn,$al->list[0]->art_id,$ATTYPE_FILES,1,1);
		$total_relfil=count($rfl->list);
		
	}

	// get Ticker articles
	$tal=new ArticleList();
	$tal->by_status="4"; // only Published in ticker articles
	$tal->status_comp="=";
	$tal->full_info=true;
	$tal->only_active_today=true; // get non-expired articles
	$tal->by_locale=$ses_locale;
	$tal->getAllArticles($dbconn,1,1);
	
	// get Modules
	$ml=new ModuleList();
	$ml->of_node=$sel;
	$ml->by_locale=$ses_locale;
	$ml->populate($dbconn,1,1);
	$mod_count=count($ml->list);
	
	// get path
	$path=$cm->getPath($sel);
	$curr_level=count($path);	
	
	// get locale
	$lr=new LocaleDBRecord($dbconn);
	$lr->locale=($ses_locale<=0)?$DEFLOC:$ses_locale;
	$lr->get();
	
	// get Template
	$tempr=new TemplateDBRecord($dbconn);
	$tempr->temp_id=$cm->getTemplate($sel);
	$tempr->get();
	$PAGE=$tempr->html;

	// get Ads
	$adl=new AdsList();    
	$adl->getAdsOfNode($dbconn,$sel,1,1);
	$ad_count=count($adl->list);		
	
// prepare tags
// [%GALLERY%]
		$GALLERY="";
	//	echo $gal->list[0]->title." ".$gal->list[1]->title." ".$gal->list[2]->title;
		
		if ($gal_count==1)
		{
			$GALLERY="<table>";
			
			for ($i=0;$i<$gal_count;$i++)
			{
				$tempgal->byid=$gal->list[$i]->gal_id;
				$tempgal->getRelAtts($dbconn);
				$n=count($tempgal->list);
				
				$gal->list[$i]->photos_per_page;
				if (!isset($nextp)) $nextp=0; 
				for ($j=$nextp;$j<($gal->list[$i]->photos_per_page+$nextp);$j+=$gal->list[$i]->photos_per_line)
				{
					$GALLERY.="<tr>";
				
						for ($k=$j;$k<$j+$gal->list[$i]->photos_per_line;$k++)
						{
						
						if ($tempgal->list[$k]->att_id2!="")	$GALLERY.="<td><a href='#' onClick=\"window.open('".$IN_MEDIA_URL.$att->getURI($tempgal->list[$k]->att_id2)."','','width=800,height=600,menubar=no,scrobars=0');\"><img src=\"".$IN_MEDIA_URL.$att->getURI($tempgal->list[$k]->att_id1)."\"  width=".$gal->list[$i]->thumbnail_width." height=".$gal->list[$i]->thumbnail_height."  border=0 alt=\"".$att->getTitle($tempgal->list[$k]->att_id2)."\"></a></td>";
						
				
						}
						$GALLERY.="</tr>";
						
				}
				$GALLERY.="<tr>";
			if ($nextp>0) $GALLERY.="<td class=text><a class=text href=content.php?sel=".$sel."&nextp=".($nextp-$gal->list[$i]->photos_per_page).">previous</a></td>";
		
				if ($j<=$n) $GALLERY.="<td class=text><a class=text href=content.php?sel=".$sel."&nextp=".$j.">next</a></td></tr>";
			
			}
			$GALLERY.="</table>";
		}
		
		$PAGE=str_replace("[%GALLERY%]",$GALLERY,$PAGE);


// [%CHARSET%]
	$CHARSET="<meta http-equiv=\"Content-Type\" content=\"text/html; charset=".$lr->iso."\">";
	$PAGE=str_replace("[%CHARSET%]",$CHARSET,$PAGE);
	
// [%CONTENT%]
// classes: art_title,art_body,art_summary
	$CONTENT="";
	for($i=0; $i<$mod_count; $i++) if($ml->list[$i]->place=="A") {
		if($ml->list[$i]->status=="I") $CONTENT.=fget_contents($IN_MODULE_DIR.$ml->list[$i]->URI);
		if($ml->list[$i]->status=="L") $CONTENT.="<a href=\"".$IN_MODULE_URL.$ml->list[$i]->URI."\">".$ml->list[$i]->title."</a>";
	}

	if($art_count>1) {
		for($i=0; $i<$art_count; $i++) {
			$ar=$al->list[$i];
			$CONTENT.="<a href=\"content.php?artid=".$ar->art_id."\">".$ar->title."</a><br><SPAN class=art_summary>".$ar->shortdesc."</SPAN><br><br>";
		} 
	} else if($art_count==1) {
		$ar=$al->list[0];
		$CONTENT.="<SPAN class=art_title>".$ar->title."</SPAN><br><br><SPAN CLASS=art_body>".$ar->fulltxt."</SPAN>";
	}	
	
	for($i=0; $i<$mod_count; $i++) if($ml->list[$i]->place=="B") {
		if($ml->list[$i]->status=="I") $CONTENT.=fget_contents($IN_MODULE_DIR.$ml->list[$i]->URI);
		if($ml->list[$i]->status=="L") $CONTENT.="<a href=\"".$IN_MODULE_URL.$ml->list[$i]->URI."\">".$ml->list[$i]->title."</a>";
	}	

	$PAGE=str_replace("[%CONTENT%]",$CONTENT,$PAGE);

// [%ADS%],[%ADS_IN(n)%]
	$ADS="";
	
	for($i=0; $i<$ad_count; $i++) {
		if($adl->list[$i]->hlink!="") {
			$htag1="<a href='".$adl->list[$i]->hlink."'>"; 
			$htag2="</a>";
		} else {
			$htag1="";
			$htag2="";
		}
		if($adl->list[$i]->att_id>0) {
			$atr=new AttachmentDBRecord($dbconn);
			$atr->att_id=$adl->list[$i]->att_id;
			$atr->get();
			$ADS.=$htag1.showAttachmentInline($atr->title,$atr->URI,$atr->mime,$atr->width,$atr->height," border=0").$htag2." ";
			
			$PAGE=str_replace("[%ADS_IN(".$i.")%]",$htag1.showAttachmentInline($atr->title,$atr->URI,$atr->mime,$atr->width,$atr->height," border=0").$htag2,$PAGE);
		} else {
			$ADS.=$htag1.$atr->adtext.$htag2;	
			
			$PAGE=str_replace("[%ADS_IN(".$i.")%]",$htag1.$atr->adtext.$htag2,$PAGE);
		}
	}
	for($i=0; $i<10; $i++) $PAGE=str_replace("[%ADS_IN(".$i.")%]","",$PAGE);	
	
	$PAGE=str_replace("[%ADS%]",$ADS,$PAGE);

// [%PATH%]
// classes: a.path
	$PATH="";
	for($i=0; $i<$curr_level; $i++) {
		$PATH.="<a href=content.php?sel=".$path[$i]->cat_id." class=path>".$path[$i]->name."</a>";
		if($i<$curr_level-1) $PATH.=" - ";
	}
	$PAGE=str_replace("[%PATH%]",$PATH,$PAGE);
		
// [%MENU%]
// classes: node_on,node_off,a.node:*,node_table
	$normal="<tr><td class=node_off>!<a href=\"content.php?sel=#\" class=node>@</a></td></tr>";
	$select="<tr><td class=node_on>!@</td></tr>";
        
        $MENU="<table class=node_table>";
	$level=0;
	$sub=$cm->getSubNodes(0,1);
	print_level($sub);
	$MENU.="</table>";
	
	$PAGE=str_replace("[%MENU%]",$MENU,$PAGE);

// [%MENU2%]

        $MENU2="<SCRIPT language=Javascript1.2 >\n";
	$level=0;
	$sub=$cm->getSubNodes($path[0]->cat_id,1);
	print_level2($sub);

        $MENU2.=" fvisited='nd0";
	for($k=1; $k<$curr_level; $k++) $MENU2.="_".$lid[$path[$k]->cat_id];	        
	$MENU2.="';\r\n</SCRIPT>";

	$PAGE=str_replace("[%MENU2%]",$MENU2,$PAGE);
	

	
// [%RELATED_FILES%]
	$RELFIL="";		
	for($i=0; $i<$total_relfil; $i++) 
		$RELFIL.="- <a href=\"".$IN_MEDIA_URL.$rfl->list[$i]->URI."\" target=_blank>".$rfl->list[$i]->title."</a><br>";

	$PAGE=str_replace("[%RELATED_FILES%]",$RELFIL,$PAGE);
		
// [%RELATED_MEDIA%],[%RELATED_MEDIA_IN(n)%]
	$RELMED="";
	for($i=0; $i<$total_relmed; $i++) {
		$RELMED.="- <a href=\"".$IN_MEDIA_URL.$rml->list[$i]->URI."\" target=_blank>".$rml->list[$i]->title."</a><br>";
	
		$PAGE=str_replace("[%RELATED_MEDIA_IN(".$i.")%]",showAttachmentInline($rml->list[$i]->title,$rml->list[$i]->URI,$rml->list[$i]->mime,$rml->list[$i]->width,$rml->list[$i]->height,""),$PAGE);	
	}
	for($i=0; $i<10; $i++) $PAGE=str_replace("[%RELATED_MEDIA_IN(".$i.")%]","",$PAGE);	
	
	$PAGE=str_replace("[%RELATED_MEDIA%]",$RELMED,$PAGE);
// [%RELATED_ARTICLES%]
	$RELART="";
	for($i=0; $i<$total_relart; $i++) 
		$RELART.="- <a href=\"content.php?artid=".$ral->list[$i]->art_id."\">".$ral->list[$i]->title."</a><br>";

	$PAGE=str_replace("[%RELATED_ARTICLES%]",$RELART,$PAGE);
// [%SEARCHFORM%]
// exposes document.searchform object
	$SF="<form action=content.php method=post name=searchform><input type=text name=query size=20></form>";
	$PAGE=str_replace("[%SEARCHFORM%]",$SF,$PAGE);

// [%TICKER%]



	function in_path($parr,$id) {
		$l=count($parr);
		$i=0;
		while($i<$l) if($parr[$i++]->cat_id==$id) return true;
		return false;
	}
	function print_level($carr) {
		global $path,$sel,$cm,$level,$normal,$select,$ses_locale,$MENU;	
		for($j=0; $j<count($carr); $j++) {
			if(in_path($path,$carr[$j]->cat_id)) $selected=true; else $selected=false;			
			if($selected) $html=$select; else $html=$normal;
			$html=str_replace("#",$carr[$j]->cat_id,$html);
			$html=str_replace("@",$cm->getName($carr[$j]->cat_id),$html);
			$html=str_replace("!",str_repeat("&nbsp;",$level*2),$html);
			$MENU.=$html;
			if($selected) {
				$sub2=$cm->getSubNodes($carr[$j]->cat_id,1);
				$level++;
				print_level($sub2);
				$level--;
			}
		} 
	}
	
	function print_level2($carr) {
		global $path,$sel,$cm,$level,$ses_locale,$MENU2,$lid;	
		$cc=count($carr);
		
		for($j=0; $j<$cc; $j++) {
			$sub2=$cm->getSubNodes($carr[$j]->cat_id,1);
			$carr[$j]->hasChildren=(count($sub2)>0)?1:0;
			$lid[$carr[$j]->cat_id]=$j;
			$level++;
			print_level2($sub2);
			$level--;
		} 
		if($cc>0) {
			$MENU2.="flAr0";
			$pp=$cm->getPath($carr[0]->cat_id);
			for($k=1; $k<=$level; $k++) $MENU2.="_".$lid[$pp[$k]->cat_id];
			$MENU2.=" = [\n";	
		}
		for($j=0; $j<$cc; $j++) {
			$MENU2.="\"".$cm->getName($carr[$j]->cat_id)."\",";
			if($carr[$j]->hasChildren) $MENU2.="\"\","; else $MENU2.="\"content.php?sel=".$carr[$j]->cat_id."\",";
			$MENU2.=" ".($carr[$j]->hasChildren+0);
			if($j<$cc-1) $MENU2.=",";
			$MENU2.="\n";
		}
		if($cc>0) $MENU2.="];\n\n";
	}
	
	function fget_contents($f) {
		$fd = fopen ($f, "r");
		$buffer="";
		while (!feof ($fd)) {
		    $buffer .= fgets($fd, 4096);
		}
		fclose ($fd);
		return $buffer;
	}

	echo $PAGE;	
?>