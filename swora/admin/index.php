<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");
$urltoserialid = "feature disabled";

///////////////////////////
// SECTIONS
///////////////////////////
	$sec = 			getsection("all");

///////////////////////////
// MODULE VERSIONS
///////////////////////////
	foreach($config as $a=>$b) {
		if(preg_match("#^version\_(.*)#i",$a,$re)) {
			$version[$re[1]] = (double)$b;
	}	}

///////////////////////////
// GET A PERSONAL LICENS KEY
///////////////////////////
	if(!$config[serialid]) {
		if($HTTP_GET_VARS["getserial"]) {
			$time = time()+(3600*24*10);
			$db->query_str("DELETE FROM $tab[config] WHERE name='nextserialcheck'");
			$db->query_str("INSERT INTO $tab[config] VALUES ('nextserialcheck','$time')");
			$infos = Array("reg[adminmail]"=>$adminmail,"reg[httpurl]"=>$httpurl,"reg[title]"=>$title,"reg[server_name]"=>$HTTP_SERVER_VARS["SERVER_NAME"],
					"reg[dbhost]"=>md5($db->db_server),"reg[software]"=>$HTTP_SERVER_VARS["SERVER_SOFTWARE"],
					"reg[hosttype]"=>$HTTP_ENV_VARS["HOSTTYPE"],"reg[ostype]"=>$HTTP_ENV_VARS["OSTYPE"]);
			$URL 	= ereg_replace("^http://", "",$urltoserialid);
			$Host 	= substr($URL, 0, strpos($URL, "/"));
			$URI 	= strstr($URL, "/");
			$ReqBody= "";
			foreach($infos as $key=>$val) {if ($ReqBody) $ReqBody.= "&";$ReqBody.= $key."=".urlencode($val);}
			$ContentLength 	= strlen($ReqBody);
			$ReqHeader	=
				"POST $URI HTTP/1.1\n".
				"Host: $Host\n".
				"User-Agent: RegSoftware\n".
				"Content-Type: application/x-www-form-urlencoded\n".
				"Content-Length: $ContentLength\n\n".
				"$ReqBody\n";
			$socket = @fsockopen($Host, 80, $errno, $errstr, 1);
			if($socket) {
				fputs($socket, $ReqHeader);
				while(fgets($socket,1024)!="\r\n");
				while(!preg_match("#^---(.*)#",$str,$re) && $h++<5)
					$str = fgets($socket,4096);
			}
			fclose($socket);
			if($re[1]){$db->query_str("INSERT INTO $tab[config] VALUES ('serialid','$re[1]')");}
			$config = getconfig();
	}	}
///////////////////////////
// LOADING ADMIN-FILES
///////////////////////////
	$dir = @dir("./sections");$i=2;
	while($sec1=$dir->read()) {
		if($sec1!="." && $sec1!="..") {
			// CHECK FOR AUTO_INSTALL
			if(@is_file("./sections/".$sec1."/auto_install.php") && !$sec[$sec1]) {
				$autoinstall[] = $sec1;
			}
			// INCLUDING THE ADMIN OR THE INSTALLED MODULES
			if(@is_file("./sections/".$sec1."/loadadmin.php") && $sec[$sec1]) {
				    if($sec1=="swora") $sections_array[0] = $sec1;
				elseif($sec1=="user")  $sections_array[1] = $sec1;
				else $sections_array[$i++] = $sec1;
	}	}	}

	// *********************** //
	// AUTOINSTALL
	// *********************** //
	if($autoinstall) {
		foreach($autoinstall as $sec1) {
			if($sec1 == $isinstalled) contiune;
			else eval("\$installs .= \"".gettemplate("admin.autoinstalls.bit")."\";");
		}
		if($installs) eval("\$autosetup = \"".gettemplate("admin.autoinstalls")."\";");
	}

	// *********************** //
	// MODULEINCLUDE
	// *********************** //
	for($i=0;$i<=count($sections_array);$i++) {
		if(!$sections_array[$i])break;
		if(is_allowed($sec[$sections_array[$i]][id])) {
			@include("./sections/".$sections_array[$i]."/loadadmin.php");
		}
	}
	// ********************** //
	// HACKS
	// ********************** //
	if($frame=="left" && !$adminaction && !$action && is_allowed($sec[swora][id])) {
		$dir = dir("./includes");
		while($f=$dir->read()) {
			if(preg_match("#^e_#",$f)) {
				$hackaction	= "adminmenu";
				$hackfile	= $f;
				@include("./includes/".$f);
			}
		}
		if(!$hackbits) eval("\$hackbits = \"".gettemplate("admin.main.leftmenu.hacks.none")."\";");
		eval("\$menu .= \"".gettemplate("admin.main.leftmenu.hacks")."\";");
	}
#######
if($inc[action]) {
	eval("style(\"".gettemplate("admin.main")."\");");
	style($inc[action]);
	style($inc[htmlfoot]);
	exit;
#######
} else {
	#######
	if(!$frame) {
		eval("style(\"".gettemplate("admin.main.frameset")."\");");
	} else {
		#######
		if($frame=="main") {
			///////////////////
			list($count_members) = $db->query("SELECT COUNT(*) FROM $tab[user]");
			list($count_blockedmembers) = $db->query("SELECT COUNT(*) FROM $tab[user] WHERE blocked='1'");
			list($count_incativemembers) = $db->query("SELECT COUNT(*) FROM $tab[user] WHERE activated='0'");
			list($count_sections) = $db->query("SELECT COUNT(*) FROM $tab[section]");
			list($count_incativesections) = $db->query("SELECT COUNT(*) FROM $tab[section] WHERE active='0'");
			list($count_errors) = $db->query("SELECT COUNT(*) FROM $tab[error]");
			if($tab[hit_counter]) 	list($hits) = $db->query("SELECT * FROM $tab[hit_counter] WHERE id='1'");
			if($tab[pm])		list($count_pms) = $db->query("SELECT COUNT(*) FROM $tab[pm]");
			if($tab[pm])		list($count_inboxpms) = $db->query("SELECT COUNT(*) FROM $tab[pm] WHERE inbox='1'");
			if($tab[pm])		list($count_outboxpms) = $db->query("SELECT COUNT(*) FROM $tab[pm] WHERE outbox='1'");
			if($tab[forum_post])	list($count_posts) = $db->query("SELECT COUNT(*) FROM $tab[forum_post]");
			if($tab[forum_post])	list($count_threads) = $db->query("SELECT COUNT(*) FROM $tab[forum_post] WHERE is_first='1'");
			if($count_threads)	$count_postsperthread = round($count_posts/$count_threads,4);
			if(!$config[serialid])	$config[serialid] = "Noch keine keine Id gehohlt. -><a href=\"index.php?section=admin&getserial=1&frame=main\">Try To Get one</a>";

			eval("\$inc[action] = \"".gettemplate("admin.main.main")."\";");
			eval("style(\"".gettemplate("admin.main")."\");");
			style($inc[action]);
			style($inc[htmlfoot]);
			exit;
		}
		#######
		elseif($frame=="left" && !$adminaction && !$action) {
			eval("style(\"".gettemplate("admin.main.frameset.left")."\");");
			exit;
		}
		#######
	}
	#######
}
#######
?>
