<?
//////////////////////////////////
//	(C)opyright by XeRoc	//
//	all rights reserved	//
//////////////////////////////////


/////////////////////////////////////////////
// FUNCTIONS
/////////////////////////////////////////////
##############################
function style($str,$echo=1) {
	global $config,$section;
	//$str = str_replace("\r\n","",$str);
	//$str = str_replace("\t","",$str);
	/////////////////////////
	// FILTER BADWORDS
	/////////////////////////
		if(trim($config[badwords]) && $section!="admin") {
			$badwords_array = explode("\r\n",$config[badwords]);
			foreach($badwords_array as $word) {
				if(!($word=trim($word))) contiune;
				else {	$wordex = "";
					for($i=0;$i<strlen($word);$i++) {
						$wordex .= "$word[$i](\s)*";
					}
					$str = preg_replace("#$wordex#iUs","\$\&{?§!\"\$%",$str);
				}
			}
		}
	if($echo) {
		echo $str;
		flush();
	} return $str;
}
#############################
function is_allowed($sectionid="any", $boardid="0", $userid="0") {
	global $tab, $db, $login;
	if(!$userid) $userid = $login[id];

	#### IS ADMIN ####
	if($db->query("SELECT id FROM $tab[right] WHERE userid='$userid' AND is_admin='1'")) return "admin";
	else {
		###### NON ADMIN #####
		if($sectionid=="any") {
			$testquery = $db->query("SELECT id FROM $tab[right] WHERE userid='$userid'");
		}
		elseif(!$boardid) {
			$testquery = $db->query("SELECT id FROM $tab[right] WHERE userid='$userid' AND section='$sectionid'");
		} else {
			$testquery = $db->query("SELECT id FROM $tab[right] WHERE userid='$userid' AND section='$sectionid' AND (boardid='$boardid' || boardid='0')");
		}

		if($testquery) return "1";
	}
	return 0;
}
#############################
function is_allowed_upload($serverid=0,$path="any",$userid=0) {
	global $tab,$db,$login,$sec;

	if(!$userid) $userid = $login[id];
	$user = getuser($userid);
	$path = str_replace("\\","/",$path);

	if(is_allowed($sec[id])) return "mod";

	if($path=="any") {
		if($user[upload_allow]) 	return 1;
		else 				return 0;
	}

	$q = $db->query_str("SELECT * FROM $tab[upload_access] WHERE serverid='$serverid' AND userid='$userid'");
	while($set = $db->fetch_array($q)) {

		$server = getftpaccesscodes($set[serverid]);
		if($server[path][0]!="/") $server[path] = "/".$server[path];
		if($server[path][strlen($server[path])-1]=="/") $server[path] = substr($server[path],0,-1);

		if($path[0]!="/") $path = "/".$path;
		if($path[strlen($path)-1]!="/") $path.="/";


		if(preg_match("#^$server[path]$set[path]#iUs",$path)) return 1;
	}

	return 0;
}
#############################
function makesmilies($text) {
	global $db,$tab;
	$squery = $db->query_str("SELECT * FROM $tab[smilie]");

	while($s = $db->fetch_array($squery)) {
		$text = str_replace("|".$s[stext]."|","<img src=\"images/smilies/".$s[sfile]."\">" , $text);
	}
	return $text;
}
#############################
function mkbetterarray($array) {
	return $array;
}
#############################
function evalphpcode($str,$userid=0) {
	global $config,$projectid,$db,$tab,$login;
	if(!$userid) $userid = "1";

	/*
	 * //////////////////////////////////////
	 * 	// INCLUDES OTHER files from project
	 * 	//////////////////////////////////////
	 * 		if($projectid) {
	 * 			////////////////////////////
	 * 			// PHP
	 * 			////////////////////////////
	 * 			if($includes = preg_match_all("#(include|require|include_once|require_once)[ ]*\(+\"(.*)\"\)+;#iUs",$str,$re)) {
	 * 				for($i=0;$i<=count($re[0]);$i++) {
	 * 					$incseq =  $re[0][$i];
	 * 					$incfile = $re[2][$i];
	 * 					$result =  $db->query("SELECT * FROM $tab[code_code] WHERE userid='$userid' AND filename='$incfile' LIMIT 1");
	 * 					if($result) {
	 * 						$inceval = evalphpcode($result[code]);
	 * 						$str = str_replace($incseq,"\r\n".$inceval[0]."\r\n",$str);
	 * 					} else {
	 * 						$str = str_replace($incseq,"// \"$incfile\" konnte nicht im aktuellen Project gefunden werden",$str);
	 * 					}
	 * 				}
	 * 
	 * 			}
	 * 			////////////////////////////
	 * 			// StyleSheets
	 * 			////////////////////////////
	 * 			preg_match_all("#<link.+href=\"(.*)\".+>#iUs",$str,$re);
	 * 			if(is_array($re)) {
	 * 				for($i=0;$i<=count($re[0]);$i++) {
	 * 					$incseq =  $re[0][$i];
	 * 					$incfile = $re[1][$i];
	 * 					$result =  $db->query("SELECT * FROM $tab[code_code] WHERE userid='$userid' AND filename='$incfile' LIMIT 1");
	 * 					if($result) {
	 * 						$str = str_replace($incseq,"\r\n<style type=\"text/css\">\r\n<!--\r\n".$result[code]."\r\n-->\r\n</style>\r\n",$str);
	 * 					} else {
	 * 						$str = str_replace($incseq,"<!-- \"$incfile\" konnte nicht im aktuellen Project gefunden werden -->",$str);
	 * 					}
	 * 				}
	 * 
	 * 			}
	 * 		}
	 */

	//////////////////////////////////////
	// CHECK FOR DISALLOWED FUNTIONS
	//////////////////////////////////////
		$lines = explode("\r\n",trim($str));
		foreach($lines as $line) {
			$replaces = explode("\r\n",$config[code_no_eval]);
			foreach($replaces as $res) {
				if(preg_match("#$res#iUs",$line))
					$line = "#".$line;
			}
			$toeval .= trim($line)."\r\n";
		}
		$toeval = trim($toeval);
		$str = $toeval;

	//////////////////////////////////////
	// SyntaxHightlightning
	//////////////////////////////////////
		if($toeval) {
			$code = highlight(trim($toeval));
		}

	//////////////////////////////////////////////
	// PHP OR NOT || JEAH ? GET THE EVAL CODES
	//////////////////////////////////////////////
		if(!preg_match("#<?(php)?#iUs",$str)) {
			$str2 = highlight($str);
			return array($str,$str2);
		} else {
			if(preg_match_all("#(<\?(php)?.*\?>)#iUs",$str,$re)) {
				for($i=0;$i<=count($re[0]);$i++) {
					$widthphp = $re[2][$i];
					$source =   $re[1][$i];
					$source =   preg_replace("#(<\?=(.*)\?>)#","echo \\2;",$source);
					//////////////////////////////////////
					// EVAL
					//////////////////////////////////////
					//	$source = addslashes(trim($source));
					//	$source = str_replace("\$","\\\$",$source);

						$source = str_replace("<?php","",$source);
						$source = str_replace("<?","",$source);
						$source = str_replace("?>","",$source);

						$eval = thiseval($source);

					//////////////////////////////////////
					// REMIX
					//////////////////////////////////////
							if($source) {
								$source = str_replace("<b>Parse error</b>","<font class=\"code_parse_parseerror\"><b>Parse Error</b></font>",$source);
								$source = str_replace("<b>Warning</b>","<font class=\"code_parse_warning\"><b>Warning</b></font>",$source);
								$source = str_replace("\r\n","<br>",$source);
							}
					//////////////////////////////////////
					// THE REPLACES
					//////////////////////////////////////
						if($re[0][$i]) {
							$str = str_replace($re[0][$i],$eval,$str);
						}
				}
			}
		}

	//////////////////////////////////////
	// RETURN
	//////////////////////////////////////
		return array($str,$code);
}
#############################
function thiseval($str) {
	$stroverride = "Tja so dumm sind wir hier net :-).<br>(no access to [\$globals|\$_cookie|\$_session])";

	if(preg_match_all("#\\\$(globals|_cookie|_session)#iUs",$str,$re)) {
		global $login;
		saveerror("Security Problem :: User \"$login[id]\"->$login[user_login] tried to access [\$globals|\$_cookie|\$_session] ",__FILE__,__LINE__,1,1);
		return $stroverride;
	} else {
		ob_start();
			echo eval("$str");
			$eval = ob_get_contents();
		ob_end_clean();
		return $eval;				
	}
}
#############################
function mksworacodes($str,$userid="1") {
	$str = " ".$str." ";
	$str = str_replace("[b]","<b>",$str);
	$str = str_replace("[/b]","</b>",$str);
	$str = str_replace("[i]","<i>",$str);
	$str = str_replace("[/i]","</i>",$str);
	$str = str_replace("[u]","<u>",$str);
	$str = str_replace("[/u]","</u>",$str);
	$str = str_replace("[center]","<center>",$str);
	$str = str_replace("[/center]","</center>",$str);
	$str = str_replace("[hr]","<hr>",$str);

	$str = str_replace("\r\n","<br>",$str);


	$img_find = 	"#\[img(.*)\](.*)\[\/img\]#iUs";
	$img_replace = 	"<img src=\"\\2\"\\1>";
	$str = preg_replace($img_find,$img_replace,$str);
	// -------------

	// URL
	$url_find = 	"#\[url(.*)\](.*)\[\/url\]#iUs";
	$url_replace = 	"<a target=\"_blank\" href=\"\\2\"\\1>\\2</a>";
	$str = preg_replace($url_find,$url_replace,$str);
	// --------------

	// EMAIL
	$mail_find = 	"#\[email(.*)\](.*)\[\/email\]#iUs";
	$mail_replace = "<a href=\"mailto:\\2\"\\1>\\2</a>";
	$str = preg_replace($mail_find,$mail_replace,$str);
	// ---------------

	// FONT
	$font_find = 	"#\[font(.*)\](.*)\[\/font\]#iUs";
	$font_replace = "<font\\1>\\2</font>";
	$str = preg_replace($font_find,$font_replace,$str);
	// ---------------

	// CODE
	$code_find = 	"#\[code\](.*)\[\/code\]#iUs";
	preg_match_all($code_find,$str,$ver);
	for($i=0;$i<=count($ver[1]);$i++) {
		$str_code_highlight = "<br>PHP:<hr align=left width=50% noshade /><div class=\"highlight_font\">" . highlight($ver[1][$i]) . "<br></div><hr align=left width=50%>";
		$str = str_replace($ver[0][$i],$str_code_highlight,$str);
	}
	// ---------------

	// PARSED CODE
	$pcode_find = 	"#\[pcode\](.*)\[\/pcode\]#iUs";
	preg_match_all($pcode_find,$str,$ver);
	for($i=0;$i<=count($ver[1]);$i++) {
		$parsed = evalphpcode($ver[1][$i],$userid);
		$str = str_replace($ver[0][$i],$parsed[0],$str);
	}
	// ---------------

	// Mail.links
	$m_find =  "#(\s)";
	$m_find .= "([a-zA-Z0-9_-]*)";
	$m_find .= "@";
	$m_find .= "([a-zA-Z0-9_-]*)";
	$m_find .= "\.(de|com|net|org|co\.uk|uk|li|at)";
	$m_find .= "(\?\S*)?";
	$m_find .= "(\s)#Uis";
	if(preg_match_all($m_find,$str,$re)) {
		for($i=0;$i<=count($re[1]);$i++) {
			$space[0] = 	$re[1][$i];
			$account=	$re[2][$i];
			$provider=	$re[3][$i];
			$domain=	$re[4][$i];
			$etc = 		$re[5][$i];
			$space[1] = 	$re[6][$i];

			$str_replace = "$space[0]<a href=\"mailto:$account@$provider.$domain$etc\" target=\"_blank\">@$account@</a>$space[1]";
			$str = str_replace($re[0][$i],$str_replace,$str);
		}
	}
	// --------------
	$url_find = "(\s)"; 					#WHITESPACE
	$url_find .= "(http://|https://|ftp://|news://)?"; 	#PROTOCOL
	$url_find .= "(www\.)?";				#www
	$url_find .= "([a-zA-Z0-9_\.\-]*)"; 			#SUBDOMAIN ? HOST
	$url_find .= "\.(de|com|net|org|at|li|sw)"; 		#DOMAIN
	$url_find .= "(.*)?"; 					#PATH
	$url_find .= "(\s)"; 					#WHITESPACE

	if(preg_match_all("#".$url_find."#iUs",$str,$re)) {
		for($i=0;$i<=count($re[1]);$i++) { 
			$space[0] =     $re[1][$i]; 
			$prot=        	$re[2][$i]; 
			$www =		$re[3][$i];
			$url=        	$re[4][$i]; 
			$domain=    	$re[5][$i];
			$path =     	$re[6][$i]; 
			$space[1] =     $re[7][$i]; 
             
			if(!$url)    	continue; 
			if(!$prot)    	$prot = "http://";
			if(!$www && !preg_match("#\.#",$url)) $www = "www.";
	
			$str_replace = "$space[0]<a href=\"$prot$www$url.$domain$path\" target=\"_blank\">$url.$domain</a>$space[1]"; 

			$str = str_replace($re[0][$i],$str_replace,$str); 
		} 
	}
	return trim($str);
}
#############################
function highlight($str) {   // html = 0;
	ob_start();

			$str = str_replace("<br>","\r\n",$str);
    		$str = str_replace("&lt;" , "<" , $str);
    		$str = str_replace("&gt;" , ">" , $str);

    		$str = str_replace("?>" , "" , $str);
    		$str = str_replace("<?php" , "" , $str);
    		$str = str_replace("<?" , "" , $str);

		@highlight_string("<?" . $str . "?>");

		$buffer = ob_get_contents();

    		$buffer = str_replace("?&gt;" , "" , $buffer);
    		$buffer = str_replace("&lt;?" , "" , $buffer);

	ob_end_clean();
	return trim($buffer);
}
#############################
function gettemplate($file,$printerror=1) {
	global $style,$counttemplateloads,$lasttemplate;

	preg_match("#([a-zA-Z0-9]*)\.#iUs",$file,$re);

	if($re[1] && @is_dir($style[templates]."/default/".$re[1])) {
		$folder = $re[1] . "/";
	}

	$file_load 	= "$style[templatefolder]/$folder$file.html";
	$file_default 	= "$style[templates]/default/$folder$file.html";


	if(@is_file($file_load)) {
		$fileentry = implode( "", file($file_load));
	} else {
		if(@is_file($file_default)) {
			$fileentry = implode( "", file($file_default));
		} else {
			if($printerror) {
				saveerror("Template :: \"$file\" konnte nicht gefunden werden",__FILE__,__LINE__,0,1);
			}
			return "Datei <b>$file</b> wurde nicht gefunden.<br>\n";
		}
	}

	//$fileentry = "\n<!--  $file //-->\n".$fileentry;
	//$fileentry = str_replace("\r","",$fileentry);
	//$fileentry = str_replace("\n","",$fileentry);
	//$fileentry = str_replace("\t","",$fileentry);


	$fileentry = str_replace("\"","\\\"",$fileentry);
	$fileentry = preg_replace("#{_([a-zA-Z0-9_\[\]]*)_}#","\$\\1",$fileentry);
	if($lasttemplate!=$file) {
		$counttemplateloads++;
		$lasttemplate = $file;
	}
	return 	$fileentry;
}
#############################
function getmenu($a) {
	global $menureset,$HTTP_COOKIE_VARS,$tab,$login,$db,$session,$session_form,$inc,$catspace,$config,$menuaction,$menuid;
	$innerfunction 		= TRUE;
	    if(!$a)			$position	= "top";
	elseif( $a=="1")	$position	= "left";
	elseif( $a=="2")	$position	= "right";
	elseif( $a=="3")	$position	= "bottom";

	if($menureset) {
		if(is_array($HTTP_COOKIE_VARS["menu"])) {
			setcookie("menu",0,-time());
		}
	}
	if($HTTP_COOKIE_VARS["menu"] || $menuaction) {
		if(is_array($HTTP_COOKIE_VARS["menu"])) {
			foreach($HTTP_COOKIE_VARS["menu"] as $c=>$d) {
				$actions[$c] = $d;
		}	}
		if($menuid && $menuaction) {
			$actions[$menuid] = $menuaction;
			setcookie("menu[$menuid]",$menuaction,time()+3600*7);
		}
	}

	if($login) 	$bits		= $db->query_str("SELECT * FROM $tab[menu] WHERE position=$a AND (showonlylogout='0' OR showonlylogin='1') ORDER BY sub_cat DESC,sort ASC");
	else	 	$bits		= $db->query_str("SELECT * FROM $tab[menu] WHERE position=$a AND (showonlylogout='1' OR showonlylogin='0') ORDER BY sub_cat DESC,sort ASC");

	while($bit=$db->fetch_array($bits)) {$sqlmenu[] = $bit;}

	if(!is_array($sqlmenu)) return;
	foreach($sqlmenu as $bit) {
			if($bit[sub_cat]!=0) {
			if($bit[mode]=="html")		{eval("\$linkbit_$bit[sub_cat] .= \"".gettemplate("include.main.menu.html.td")."\";");}
			elseif($bit[mode]=="link")	{eval("\$linkbit_$bit[sub_cat] .= \"".gettemplate("include.main.menu.$position.td")."\";");}
		}
		if($bit[sub_cat]==0) {
			$linkbit = "linkbit_$bit[id]"; $linkbit = $$linkbit;
			if($bit[mode]=="script") {
				if(@is_file("./includes/".$bit[script]))
					@include("./includes/".$bit[script]);
				else 	saveerror("Invalid Hack $bit[script]",__FILE__,__LINE__);
			}
			elseif($bit[mode]=="html")		{
				eval("\$catbit .= \"".addslashes($bit[html])."\";");
			}
			elseif($bit[mode]=="cat")		{
				eval("\$catbit .= \"".gettemplate("include.main.menu.$position.th")."\";");
			}
			$catbit .= "\n".$catspace;
	}	}
	eval("\$menu =\"".	gettemplate("include.main.menu.$position")  . "\";");
	$innerfunction 		= FALSE;
	return $menu;
}
#############################
function getconfig() {
	global $tab,$db;
	$q = $db->query_str("SELECT * FROM $tab[config]");
	while($a = $db->fetch_array($q)) {
		$re[$a[name]] = $a[value];
	}
	return $re;
}
#############################
function gettabs() {
	global $tab,$db,$config;
	//$q = $db->query_str("SELECT * FROM $tab[config]");
	//while($a = $db->fetch_array($q)) {
	foreach($config as $a=>$b) {
		if(preg_match("#tab_(.*)#",$a,$re)) {
			$return[$re[1]] = $b;
		}
	}
	return $return;
}
#############################
function getpoints() {
	global $tab,$db,$config;

	$re[news_activate] = 		$config[p_news_activate];
	$re[news_com] = 			$config[p_news_com];
	$re[pm_write] = 			$config[p_pm];
	$re[forum_newpost] = 		$config[p_forum_newpost];
	$re[forum_newthread] = 		$config[p_forum_newthread];
	$re[rateing_freesource] = 	$config[p_rating_freesource];
	foreach($config as $a=>$b) {
		if(preg_match("#^p_(.*)#",$a,$ret)) {
			$re[$ret[1]] = $b;
		}
	}
	return $re;
}
#############################
function getuser($userid,$new=0) {
	global $tab, $db,$getuserpuffer;
	static $getuserpuffer;

	if($new) return $db->query("SELECT * FROM $tab[user] WHERE id='$userid'");

	if(!is_array($getuserpuffer)) {
		$return = $db->query("SELECT * FROM $tab[user] WHERE id='$userid'");
		$getuserpuffer[] = $return;
	} else {
		foreach($getuserpuffer as $a=>$b) {
			if($b[id]==$userid) {
				$return = $b;$found=1;break;
			}
		}
		if(!$found) {
			$return = $db->query("SELECT * FROM $tab[user] WHERE id='$userid'");
			$getuserpuffer[] = $return;
		}
	}

	if($tab[useroption]) {
		$tmp = $db->query_str("SELECT * FROM $tab[useroption] WHERE userid='$userid'");
		while($option = $db->fetch_array($tmp)) {
			$return["$option[name]"] = $return[value];
		}
	} else {
		saveerror("UPDATE ausführen. Siehe Admin",__FILE__,__LINE__);
	}

	return $return;
}
#############################
function getsection($sectionid="",$sectionname="0") {
	global $tab,$db;
	if($sectionid=="all") {
		$query = $db->query_str("SELECT * FROM $tab[section]");
		while($section = $db->fetch_array($query)) {
			$return[$section[id]] = $section;
			$return[$section[title]] = $section;
		}
		return $return;
	} else {
		if($sectionid)
			$querysec = $db->query("SELECT * FROM $tab[section] WHERE id='$sectionid'");
		if($sectionname)
			$querysec = $db->query("SELECT * FROM $tab[section] WHERE title='$sectionname'");

		return $querysec;
	}
}
#############################
function getsmiliesbit($template) {
	global $tab,$db;
	$squery = $db->query_str("SELECT * FROM $tab[smilie]");
	if($squery) {
		while($s = $db->fetch_array($squery)) {
			eval("\$smilies .= \"".gettemplate($template)."\r\n\";");
		}
	}
	return $smilies;
}
##############################
function getugb($id) {
	global $tab,$db;
	return $db->query("SELECT * FROM $tab[ugb] WHERE id='$id'");
}
#############################
function gettmp($name,$a=0,$b=0,$c=0) {
	global $tab,$db;
	if($a) $p[] = "parama='$a'";
	if($b) $p[] = "paramb='$b'";
	if($c) $p[] = "paramc='$c'";
	return $db->query("SELECT id FROM $tab[tmp] WHERE name='$name' AND ".implode(" AND ",$p));
}
#############################
function savetmp($name,$a=0,$b=0,$c=0) {
	global $tab,$db;
	$db->query_str("INSERT INTO $tab[tmp] (name,parama,paramb,paramc) VALUES ('$name','$a','$b','$c')");
}
#############################
function getuseravatar($userid=0) {
	global $tab,$db,$config,$login,$ftp;
	if(!$userid)$userid	= $login[id];
	$accesscodes 		= getftpaccesscodes($config[avatar_ftpid]);
	if($query		= $db->query("SELECT * FROM $tab[avatar] WHERE userid='$userid' LIMIT 1")) {
		if($query[path]) {
			if($fp=@fopen("images/avatar/".$query[path],"rb")) {
				if(!$fp) return 0;
				while(!feof($fp)) {
					$image = fread($fp,999999);
				}
				fclose($fp);
				return $image;
			} else {
				return implode("",file("./images/spacer.gif"));
			}
		}
	}
	return 0;
}
#############################
function mkdate($timestamp=0, $sort=0) {
	if(!$timestamp) $timestamp = time();
	if(!$sort) $sort = "d. M, H:m";
	return @date($sort,$timestamp);
}
#############################
function mkuser($mode, $userid, &$userref) {
	global $db,$tab,$useronline;
	if(!$userref) {	$user = getuser($userid);} else {$user = $userref;}

	switch($mode) {
		###############
		case "user_hp":
			$user[user_hp] = mk2url($user[user_hp],0);
		break;
		###############
		case "user_signatur":
			if(!$user[user_signatur]) return;
			$user[user_signatur] = makesmilies(mksworacodes(htmlspecialchars($user[user_signatur])));
		break;
		###############
		case "user_text":
			$user[user_text] = makesmilies(mksworacodes($user[user_text]));
		break;
		###############
		case "user_gender":
			if($user[user_gender]==1) $user[user_gender] = "männlich";
			if($user[user_gender]==2) $user[user_gender] = "weiblich";
		break;
		###############
		case "user_email":
			switch($user[show_email]) {
				case TRUE:
					$mode .= ".mail";
				break;
				default:
					$mode .= ".nomail";
				break;
			}
		break;
		$dir = dir("./includes");
		while($f = $dir->read()) {
			if(preg_match("#^u_#",$f))
				include("./includes/".$f);
		}
		###############
		case "avatar":
			if(!$db->query("SELECT * FROM $tab[avatar] WHERE userid='$user[id]'")) return "";
		break;

	}

	if($mode=="user_icq" && !$user[user_icq]) {return "---";}
	if($mode=="user_aim" && !$user[user_aim]) {return "---";}
	if($mode=="user_yim" && !$user[user_yim]) {return "---";}
	if($mode=="user_yim" && !$user[user_yim]) {return "---";}
	if($mode=="user_email" && !$user[user_email]) {	return "---";}
	if($mode=="user_hp" && !$user[user_hp]) {return "---";}

	eval("\$return = \"".gettemplate("user.show.$mode")."\";");
	return $return;
}
#############################
function mkdetails($mode,$value) {		// for values
	$user[$mode] = $value;

	return mkuser($mode,0,$user);
}
#############################
function mkstatus($a=0,$b=0,$gtor=0) {
	if(!$b) return 0;
	$percent = ($a / $b) *100;
	if(!@is_file("images/voting.gif")) {
		$percent = 100-$percent;
		$return .= "<table cellpadding=0 cellspacing=0><tr height=10>";
	  
		  	if($gtor) {
		   	$colorr = hexdec("20");
	  		$colorg = hexdec("FF");
		  	while(	$colorr < hexdec("FF") &&
		  		$colorg > hexdec("00") &&
	  			$percent<=100) {
	 			$r = DecHex($colorr+=2);
		 		$g = DecHex($colorg-=2);
		 		$return .= "<td bgcolor=\"".$r.$g."00\" width=2></td>";
	 			$percent++;
		 	}
		  } else {
	 		$colorr = hexdec("FF");
		 	$colorg = hexdec("20");
		 	while(	$colorr > hexdec("00") &&
		 		$colorg < hexdec("FF") &&
		 		$percent<=100) {
	 			$r = DecHex($colorr-=2);
	 			$g = DecHex($colorg+=2);
				$return .= "<td bgcolor=\"".$r.$g."00\" width=2></td>";
				$percent++;
			}
		}
		$return .= "</tr></table>";
		return $return;
	} else {
		return "<img src=\"./images/voting.gif\" height=7 width=$percent% alt='Votes: $percent%'>";
	}
}
#############################
function addpoints($points, $userid=0) {
	global $tab, $login, $db;
	if(!$userid) $userid = $login[id];
	$do = $db->query_str("UPDATE $tab[user] SET points=points+'$points' WHERE id='$userid'");
	return 1;
}
#############################
function checkmail($mail) {
	return preg_match('#^[a-z0-9\.\-_]+@[a-z0-9\-_]+(\.([a-z0-9\-_]+\.)*?[a-z]+$)?#iUs', $mail);
}
#############################
function generatepass($lang=5) {
	$passobj = new randompasswort;
		$passobj->do_alpha = 	1;
		$passobj->do_num = 	1;
		$passobj->do_son = 	0;
	return $passobj->rand($lang);
}
#############################
function getcommunitylines($pfad=".") {
	$lines=0;
	$lines+=getcommunitylines_rek("./admin");
	$lines+=getcommunitylines_rek("./classes");
	$lines+=getcommunitylines_rek("./includes");
	$lines+=getcommunitylines_rek("./sections");
	$lines+=getcommunitylines_rek("./templates");
	$dir = dir($pfad);
	while($file = $dir->read()) {
		if($file != "." && $file != "..") {
			if(@is_file($file)) {$lines += getfilelines("$pfad/$file");}
		}
	}
	return $lines;
}
#############################
function getcommunitylines_rek($pfad="") {
	$dir = dir($pfad);
	while($file = $dir->read()) {
		if($file != "." && $file != "..") {
			if(is_dir("$pfad/$file")) 	{$lines += getcommunitylines_rek("$pfad/$file");}
			else 				{$lines += getfilelines("$pfad/$file");}
		}
	}
	return $lines;
}
#############################
function getfilelines($scriptpfad) {
	return count(file($scriptpfad));
}
#############################
function saveerror($text,$filename=0,$line=0,$domail=0,$saveindb=1) {
	global $db,$tab,$sendmail,$adminmail,$REQUEST_URI,$httphost;

	if(is_array($text)) 	{foreach($text as $a) {$stext .= "$a\r\n";}}
	else 			{$stext = $text;}

	$text 	= addslashes($text);
	$stext 	= addslashes($stext);

	if($saveindb && is_object($db)) {
		if(!$db->query("SELECT id FROM $tab[error] WHERE text='$stext'")) {
			$db->query_str("INSERT INTO $tab[error] (request_uri,scriptname,line,text) VALUES ('$REQUEST_URI','$filename','$line','$stext') ");
		}
	}
	if($domail && is_object($sendmail)) 		{$sendmail->mail($adminmail,"!!!! Warning !!!",$stext,"FROM: ERROR@$httphost",0);}
	if(!@is_file("errors.html")) @touch("errors.html",0777);
	$fileentry = @implode("",@file("errors.html"));
	if(@is_writeable("errors.html")) {
		$fp = @fopen("errors.html","w");
			$text = "<pre><hr width=100% noshade>".mkdate()." | <i>File: $filename <br>Line: $line</i><br>$text</pre>";
			$text.=$fileentry;
			@fputs($fp,$text);
		@fclose($fp);
	}
}
#############################
function parseurl($str) {
	$str = str_replace("\\","/",$str);
	$str = str_replace("","/",$str);
	$str = preg_replace("#(http://|https://|ftp://|news://)(www\.)?#","",$str);
	$a = explode("/",$str);
	for($i=1;$i<=count($a);$i++) {
		if($a[$i])
			$path[] = $a[$i];
	}
	$b[host] = $a[0];
	if(is_array($path))
		$b[path] = implode("/",$path);
	return $b;
}
#############################
function mk2url($str,$tohtml=1) {
	$str = " ".$str." ";
	$url_find = "(\s)"; 					#WHITESPACE
	$url_find .= "(http://|https://|ftp://|news://)?"; 	#PROTOCOL
	$url_find .= "(www\.)?";				#www
	$url_find .= "([a-zA-Z0-9_\.\-]*)"; 			#SUBDOMAIN ? HOST
	$url_find .= "\.(de|com|net|org|at|li|sw)"; 		#DOMAIN
	$url_find .= "(.*)?"; 					#PATH
	$url_find .= "(\s)"; 					#WHITESPACE

	if(preg_match_all("#".$url_find."#iUs",$str,$re)) {
		for($i=0;$i<=count($re[1]);$i++) { 
			$space[0] =     $re[1][$i]; 
			$prot=        	$re[2][$i]; 
			$www =		$re[3][$i];
			$url=        	$re[4][$i]; 
			$domain=    	$re[5][$i];
			$path =     	$re[6][$i]; 
			$space[1] =     $re[7][$i]; 
             
			if(!$url)    	continue; 
			if(!$prot)    	$prot = "http://";
			if(!$www && !preg_match("#\.#",$url)) $www = "www.";
	
			if($tohtml) {
				$str_replace = "$space[0]<a href=\"$prot$www$url.$domain$path\" target=\"_blank\">$url.$domain</a>$space[1]"; 
			} else {
				$str_replace = "$prot$www$url.$domain$path";
			}

			$str = str_replace($re[0][$i],$str_replace,$str); 
		} 
	}
	return trim($str);
}
############################
function getmicrotime() {
	$t = explode(" ",microtime());
	return bcadd($t[1],$t[0],6);
}
############################
function rekursive_load_dirs($p=".") {
	if($p[strlen($p)-1]=="/") $p = substr($p,0,-1);
	$dir = dir($p);
		while($f=$dir->read()) {
			if(is_dir("$p/$f") && $f[0]!=".") {
				$return .= "<option value=\"$p/$f\">$p/$f</option>\r\n";
				$init = rekursive_load_dirs("$p/$f");
				$return .= $init;
			}
		}
	return $return;
}
############################
function rekursive_load_dirs_ftp($p="",$lfp="") {
	global $ftp;
	if(!$p) $p = $ftp->cpwd();

	if($p!=".") if($p[0]!="/")	$p = "/".$p;
	if($p[strlen($p)-1]!="/") 	$p .= "/";

	if($lfp!=".") if($lfp[0]!="/")	$lfp = "/".$lfp;
	if($lfp[strlen($lfp)-1]!="/") 	$lfp .= "/";


	$folders .= "<option value=\"$p\">$p</option>\r\n";

	$filelist = $ftp->crawlist($p);
	foreach($filelist as $line) {
		$preg  = "(\S{10})\s+";
		$preg .= "(\d+)\s+";
		$preg .= "(\S*)\s+";
		$preg .= "(\S*)\s+";
		$preg .= "(\d*)\s+";
		$preg .= "(\S*)\s+";
		$preg .= "(\d*)\s+";
		$preg .= "(\d{1,2}:\d{1,2})\s+";
		$preg .= "(.*)";

		preg_match("#$preg#i",$line,$re);
		$attrib 	= trim($re[1]);
		$f 	= trim($re[9]);

		if(strtolower($attrib[0])=="d" && $f[0]!=".") {
			$init 		 = rekursive_load_dirs_ftp($p.$f,$lfp);
			$folders 	.= $init[0];
			$files 		.= $init[1];
		} else {
			if(strtolower($lfp)==strtolower($p)) {;
				$files .= "<option value=\"$p$f\">$f</option>\r\n";
			}
		}

	}

	return array($folders,$files);
}
############################
function rekursive_del_dir($p=0) {
	global $ftp;
	$array = $ftp->cnlist($p);
	if(is_array($array)) {
		foreach($array as $file) {
			if($ftp->csize($p."/".$file)==-1) {
				rekursive_del_dir($p."/".$file);
			} else {
				$ftp->cdelete($p."/".$file);
			}
		}
	} else { return 0; }
	return $ftp->crmdir($p."/".$file);
}
############################
function savetraffic($traffic) {
	global $tab,$db,$section;

	if($section!="error") {
		$month = mktime(0,0,0,date("n"),date("j"),date("y"));
		if(!$db->query("SELECT * FROM $tab[traffic] WHERE date='$month'")) {
			$db->query_str("INSERT INTO $tab[traffic] VALUES ('$traffic','$month')");
		} else {
			$db->query_str("UPDATE $tab[traffic] SET traffic=traffic+'$traffic' WHERE date='$month'");
		}
	}
	$db->query_str("UPDATE $tab[config] SET value=value+'$traffic' WHERE name='traffic'");

	return 1;
}
##################################
function getdbtablestatus($tablename="all") {
	global $db;
		$g = $db->query_str("SHOW table STATUS");
		while($f = $db->fetch_array($g)) {
			if($tablename=="all") {$pvar[$f[Name]] = $f;}
			elseif(strtolower($tablename)==strtolower($f[Name])) return $f;
		}
		return $pvar;
}
##################################
function get_table_def($table) {
	global $db;
	$schema_create 				.= "DROP TABLE IF EXISTS $table;\r\n";
	$schema_create 				.= "CREATE TABLE $table (";

	$result = $db->query_str("SHOW FIELDS FROM $table");
	$count = mysql_num_rows($result);
	while($row = $db->fetch_array($result)) {
		$count--;
		$schema_create 			.= "\r\n   $row[Field] $row[Type]";
		if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
						$schema_create 	.= " DEFAULT '$row[Default]'";
		if($row["Null"] != "YES") 	$schema_create 	.= " NOT NULL";
		if($row["Extra"] != "")		$schema_create 	.= " $row[Extra]";
		if($count)			$schema_create  .= ",";
	}

	$result = $db->query_str("SHOW KEYS FROM $table");
	while($row = $db->fetch_array($result)) {
		$kname		= $row['Key_name'];
		if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
			$kname	= "UNIQUE|$kname";
		if(!isset($index[$kname]))
			$index[$kname] = array();
		$index[$kname][] = $row['Column_name'];
	}

	foreach($index as $x=>$columns) {
		$schema_create .= ",";
		if($x == "PRIMARY")			$schema_create .= "\r\n   PRIMARY KEY (" . implode($columns, ", ") 			. ")\r\n";
		elseif (substr($x,0,6) == "UNIQUE")	$schema_create .= "\r\n   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") 	. ")\r\n";
		else					$schema_create .= "\r\n   KEY $x (" . implode($columns, ", ") 			. ")\r\n";
	}

    	$schema_create .= ");";
    	return (stripslashes($schema_create));
}
##################################
function get_table_content($table) {
	global $db;
	$result = $db->query_str("SELECT * FROM $table");
	while($row = $db->fetch_row($result)) {
		$count = $db->num_fields($result);
		$table_list = "(";
		for($j=0; $j<$count;$j++) {
			$table_list .= $db->field_name($result,$j);
			if($count-$j>1)	$table_list .= ",";
		}
		$table_list .= ")";

		$schema_insert = "INSERT INTO $table $table_list VALUES (";

		$count = $db->num_fields($result);
		for($j=0; $j<$count;$j++) {
			if(!isset($row[$j]))	$schema_insert .= " ULL";
			elseif($row[$j] != "")	$schema_insert .= "'".str_replace("\r\n","\\r\\n",addslashes($row[$j]))."'";
			else			$schema_insert .= "''";
			if($count-$j>1)		$schema_insert .= ",";
		}

		$schema_insert .= ")";
		$schema_insert = trim($schema_insert);

		

		if(empty($asfile)) 	{$return .= htmlspecialchars("$schema_insert;");}
		else 			{$return .= "$schema_insert;";}
					$return  .= "\r\n";
		$i++;
	}
	return $return;
}
#################################
function getftpaccesscodes($id=0) {
	global $tab,$db;
	if($id) {
		if($result = $db->query("SELECT * FROM $tab[ftp] WHERE id='$id'"))
		return $result;
		return 0;
	} else {
		$result = $db->query_str("SELECT * FROM $tab[ftp] ORDER BY id");
		while($server = $db->fetch_array($result)) {
			$re[$server[id]] = $server;
		}
		return $re;
	}
}
################################
function formatfsize($bytes) {
			$tmp = round($bytes,4);			$value="Byte";
	if($tmp>=1024) {$tmp = round($bytes/1024,4);		$value="KByte";}
	if($tmp>=1024) {$tmp = round($bytes/1024/1024,4);	$value="MByte";}
	if($tmp>=1024) {$tmp = round($bytes/1024/1024/1024,4);	$value="GByte";}
	if($tmp>=1024) {$tmp = round($bytes/1024/1024/1024/1024,4);	$value="TByte";}
	return array(
			size => $tmp,
			value => $value
			);
}
#################################
function stripslashes_array($array) {
	foreach($array as $a=>$b) {
		$rarray[$a] = stripslashes($b);
	}
	return $rarray;
}
#################################
function mkpages($count,$max,$bit,$page=0) {
	global $REQUEST_URI,$show,$sort,$order,$param;
	if(!$max) return FALSE;
	if($count > $max) {
		while( $count > 0 ) {
			++$seite;
			$count -= $max;
			eval("\$links .= \"".gettemplate("$bit")."\";");
			$start += $max;
		}
		if($page)eval("\$sites = \"".gettemplate("$page")."\";");
	}
	if($page)	return $sites;
	else		return $links;
}
#################################
function compressoutput($output) { 
	global $config;
	if(!$config[gziplevel]) $config[gziplevel]=1;
	return gzencode($output,$config[gziplevel]);
}
#################################

####################################################################
####################################################################
####################################################################
####################################################################
//////////////////////////////////////////
// LOAD MODUL FUNCTIONS IN ./includes/
//////////////////////////////////////////
$dir = dir("./includes");
while($f = $dir->read()) {
	if(preg_match("#^f_#",$f)) {
		@include("./includes/".$f);
	}
}
unset($dir,$f);
?>
