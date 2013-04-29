<?
	
// *** DB settings ***
	$DB_NAM="vezos";
	$DB_SRV="localhost";
	$DB_USR="root";
	$DB_PAS="";

// *** Attachment upload directory ***
	$IN_MEDIA_DIR="/usr/local/apache-1.3.28/htdocs/media/stcmt/media/";
	$IN_MEDIA_URL="/media/stcmt/media/";

// *** Module upload directory ***
	$IN_MODULE_DIR="/usr/local/apache-1.3.28/htdocs/media/stcmt/mods/";
	$IN_MODULE_URL="/media/stcmt/mods/";

// *** Lock first level of nodes?
	$LOCK_FIRST_LEVEL=false;
	
// *** Default locale
	$DEFLOC=1;
			
// *** User status constants ***
	$in_userstat["A"]="Active";
	$in_userstat["I"]="Inactive";
		
// *** Cat status constants ***
	$in_catstat["A"]="Public";
	$in_catstat["P"]="Private";
	$in_catstat["I"]="Inactive";

// *** Cat param constants
	$in_catparam[1]="Articles";
	$in_catparam[2]="Media";
	$in_catparam[3]="Files";
	
// *** Article status constants ***
	$in_artstat["0"]="Inactive";
	$in_artstat["1"]="Edited";
	$in_artstat["2"]="Checked";
	$in_artstat["3"]="Published";
	$in_artstat["4"]="Published in ticker";

// *** Gallery Status Constants ***
	$in_galstat["A"]="Active";
	$in_galstat["I"]="Inactive";
		
// *** Attachment status constants ***
	$in_attstat["A"]="Active";
	$in_attstat["I"]="Inactive";

// *** Ads status constants ***
	$in_adstat["A"]="Active";
	$in_adstat["I"]="Inactive";

// *** Template status constants ***
	$in_tempstat["A"]="Active";
	$in_tempstat["I"]="Inactive";
	
// *** Module status constants ***
	$in_modstat["I"]="Inline";
	$in_modstat["L"]="Hyperlinked";

// *** Module place constants ***
	$in_modplace["A"]="Before content";
	$in_modplace["B"]="After content";

// *** Member status constants ***
	$in_memstat["A"]="Active";
	$in_memstat["I"]="Inactive";

// *** Member mailing list part. constants ***
	$in_mlstat["Y"]="Yes";
	$in_mlstat["N"]="No";
				
// *** Attachment types
	$ATTYPE_MEDIA=2;
	$ATTYPE_FILES=3;
	$ATTYPE_THUMB=4;
	$ATTYPE_GALLERY=5;

// *** Content publishing types
	$in_pubcont["N"]="One article: title/long - multiple articles: linked_title/short";
	$in_pubcont["D"]="One article: title/long - multiple articles: linked_title/short or title/long";
	$in_pubcont["F"]="One article: title/short/long - multiple articles: linked_title/short";
	$in_pubcont["A"]="One article: title/short/long - multiple articles: linked_title/short or title/long";
		
// *** Relationships
	$in_rel[1]="Related article";
	$in_rel[$ATTYPE_MEDIA]="Related media";
	$in_rel[$ATTYPE_FILES]="Related files";
	
	
// *** Users' permission bits ***
	$BIT_READ=1;
	$BIT_MOD=2;
	$BIT_ADD=4;
	$NoOfBits=3;
	$DEFAULT_MASK="731";	
	$in_permcat[0]="Users";
	$in_permcat[1]="Nodes";
	$in_permcat[2]="Articles";
	$in_permcat[3]="Media";
	$in_permcat[4]="Files";
	$in_permcat[5]="Templates";
	$in_permcat[6]="Config";
	$in_permcat[7]="Ads";
	$in_permcat[8]="Members";
	$in_permcat[9]="Modules";
	$in_permcat[10]="Keywords";
	$in_permcat[11]="Newsletter";
	$in_permcat[12]="Photo Gallery";
		
// *** Session initialization ***
	function begin_console_session() {
		session_start();
		session_cache_limiter('private_no_expire');
		session_register("ses_username");
		session_register("ses_userid");
		session_register("ses_userperms");
		session_register("ses_groupid");
		session_register("ses_startcat");
		session_register("ses_maxartstat");
	}

	function start_site_session() {
		global $DEFLOC,$ses_locale;
		session_start();
		session_register("mem_username");
		session_register("mem_id");
		session_register("ses_locale");
		
		if($ses_locale<=0) $ses_locale=$DEFLOC;
	}
		
// *** Global functions ***
	function SendError($str) {
		echo "<BODY onLoad=\"alert('".$str."'); history.back();\">\n";
		exit();
	}	
	
	function timestamp2date($ts) {
		if($ts>0) return date("Y-m-d H:i",$ts); else return "";
	}
	function date2timestamp($d) {
		if($d!="") return strtotime($d); else return 0;
	}

	function is_allowed($imod,$bit) {
		global $ses_userperms;
		return ((0+substr($ses_userperms,$imod,1)) & $bit);
	}

	function has_perm($perm,$imod,$bit) {
		return ((0+substr($perm,$imod,1)) & $bit);
	}
	
	function allowed2do($ouid,$ogid,$rperms,$bit) {
		global $ses_userid,$ses_groupid;
		if( $ses_userid==1 ) return true;
		if( $ses_userid==$ouid && has_perm($rperms,0,$bit) ) return true;
		if( $ses_groupid==$ogid && has_perm($rperms,1,$bit) ) return true;
		if( has_perm($rperms,2,$bit) ) return true;
		return false;
	}	
	function print_options($arr,$sel) {
		$k=array_keys($arr);
		for($i=0; $i<count($k); $i++) {
			echo "<option value=".$k[$i];
			if($sel==$k[$i]) echo " selected";
			echo ">".$arr[$k[$i]]."</option>";
		} 	
	}	
	
	function getStyleSheet() {
		global $HTTP_SERVER_VARS;
		if(strstr($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"MSIE"))
			return "<LINK REL=StyleSheet TYPE='text/css' HREF='../common/ie.css'>";
		else
			return "<LINK REL=StyleSheet TYPE='text/css' HREF='../common/simple.css'>";
	}
	
	function showRecProps($cdate,$udate) {
		global $HTTP_SERVER_VARS;
		if(strstr($HTTP_SERVER_VARS["HTTP_USER_AGENT"],"MSIE")) {		
			echo "<DIV ID=rdetlink style=\"position: absolute; left:11; top:16; visibility:visible;\">\n";
			echo "<a href=# onClick=\"document.all['rdet'].style.visibility=(document.all['rdet'].style.visibility=='hidden')?'visible':'hidden';\" onMouseOver=\"document.all['rdet'].style.visibility='visible';\" onMouseOut=\"document.all['rdet'].style.visibility='hidden';\"><img src=../common/img/calend.gif width=20 border=0></a>\n";
			echo "</DIV>\n";
			echo "<DIV ID=rdet style=\"position: absolute; left:30; top:17; visibility:hidden;\">\n";
			echo "<table border=0 cellspacing=0 cellpadding=2 class=tab>\n";
			echo "<tr bgcolor=#223355>\n";
			echo "<td>Creation date :</td><td>".timestamp2date($cdate)."</td>\n";
			echo "</tr>\n";
			echo "<tr bgcolor=#223355>\n";
			echo "<td>Last update :</td><td>".timestamp2date($udate)."</td>\n";
			echo "</tr>\n";
			echo "</table>\n";
			echo "</DIV>\n";
		} //@todo: 4 netscape
	}

	function showAttachmentInline($title,$URI,$mime,$w,$h,$more="") {
		global $IN_MEDIA_URL;
		$ret="";
		if(strstr($mime,"image/")) {
			$ret="<img src=\"".$IN_MEDIA_URL.$URI."\" ";
			if($w>0) $ret.="width='".$w."' ";
			if($ht>0) $ret.="height='".$h."' ";
			if($more!="") $ret.=$more;
			$ret.=">";
		} else if(stristr($mime,"audio/")) {
			$ret.="<embed src=\"".$IN_MEDIA_URL.$URI."\" width=0 height=0 autostart=true controls=false ";
			if($more!="") $ret.=$more;
			$ret.="></embed>";
		} else if(stristr($mime,"application/x-shockwave-flash")) {
			$ret="<embed src=\"".$IN_MEDIA_URL.$URI."\" ";
			if($w>0) $ret.="width='".$w."' ";
			if($h>0) $ret.="height='".$h."' ";
			if($more!="") $ret.=$more;
			$ret.=" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\"></embed>";
		} else $ret="<a href=\"".$IN_MEDIA_URL.$URI."\" ".$more.">".$title."</a>";
		return $ret;
	}
?>
