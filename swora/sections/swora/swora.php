<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

$defaultsections = Array('user','tmp','news','loader','error','swora','uploader','dl','contact','profil','help');

#####################
if($adminaction=="save_mainconfig") {
	if(!$form[smtp_use]) $form[smtp_use] = "0";

	foreach($form as $name=>$value) {
		$db->query_str("DELETE FROM $tab[config] WHERE name='$name'");
		$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$value','$name')");
	}
	$config = getconfig();
	$adminaction="mainconfig";unset($form);
}
#####################
if($adminaction=="save_bannes") {
	foreach($form as $name=>$value) {
		$db->query_str("DELETE FROM $tab[config] WHERE name='$name'");
		$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$value','$name')");
	}
	$config = getconfig();
	$adminaction="bannes";unset($form);
}
#####################
if($adminaction=="menu_new_save") {

	$position	= $form[position];
	$subcat 	= $form[subcat];
	$mode		= $form[sort];

	if($mode!="link") 						{$form[href]="";}
	if($mode=="script" && !is_file("./includes/".$form[script]))	{eval("\$fail_new_menu = \"".gettemplate("fail.upload.invalidfile")."\";");}
	if($form[showonlylogout] && $form[showonlylogin])		{eval("\$fail_new_menu = \"".gettemplate("admin.sworaconfig.fail.menu.bothlog")."\";");}
	else{
		if($mode=="link") {
			$savequery 	= $db->query_str("INSERT INTO $tab[menu] (mode,position,sub_cat,title,link,target,showonlylogin,showonlylogout)
							VALUES ('link','$position','$subcat','$form[title]','$form[href]','$form[target]','$form[showonlylogin]','$form[showonlylogout]')");
		}
		if($mode=="script") {
			$savequery 	= $db->query_str("INSERT INTO $tab[menu] (mode,position,script,showonlylogin,showonlylogout)
							VALUES ('script','$position','$form[script]','$form[showonlylogin]','$form[showonlylogout]')");
		}
		if($mode=="cat") {
			$savequery 	= $db->query_str("INSERT INTO $tab[menu] (mode,position,title,showonlylogin,showonlylogout)
							VALUES ('cat','$position','$form[title]','$form[showonlylogin]','$form[showonlylogout]')");
		}
		if($mode=="html") {
			$savequery 	= $db->query_str("INSERT INTO $tab[menu] (mode,position,sub_cat,html,showonlylogin,showonlylogout)
							VALUES ('html','$position','$subcat','$form[html]','$form[showonlylogin]','$form[showonlylogout]')");
		}
		unset($form,$mode,$position,$subcat,$adminaction);
		$adminaction	= "menu_edit";
	}
}
#####################
if($adminaction=="menu_edit_save") {

	$position	= $form[position];
	$subcat 	= $form[subcat];
	$mode		= $form[sort];

	if($mode!="link") 						{$form[href]="";}
	if($mode=="script" && !is_file("./includes/".$form[script]))	{eval("\$fail_new_menu = \"".gettemplate("fail.upload.invalidfile")."\";");}
	if($form[showonlylogout] && $form[showonlylogin])		{eval("\$fail_new_menu = \"".gettemplate("admin.sworaconfig.fail.menu.bothlog")."\";");}
	else{
		if($mode=="link") {
			$savequery 	= $db->query_str("UPDATE $tab[menu] SET
										mode='link',
										position='$position',
										title='$form[title]',
										link='$form[href]',
										target='$form[target]',
										sub_cat='$subcat',
										showonlylogin='$form[showonlylogin]',
										showonlylogout='$form[showonlylogout]' 
									WHERE id='$linkid'");
		}
		if($mode=="script") {
			$savequery 	= $db->query_str("UPDATE $tab[menu] SET	
										mode='script',
										position='$position',
										script='$form[script]',
										showonlylogin='$form[showonlylogin]',
										showonlylogout='$form[showonlylogout]' 
									WHERE id='$linkid'");

		}
		if($mode=="cat") {
			$savequery 	= $db->query_str("UPDATE $tab[menu] SET
										mode='cat',
										position='$position',
										title='$form[title]',
										sub_cat='$subcat',
										showonlylogin='$form[showonlylogin]',
										showonlylogout='$form[showonlylogout]' 
									WHERE id='$linkid'");

		}
		if($mode=="html") {
			$savequery 	= $db->query_str("UPDATE $tab[menu] SET
										mode='html',
										position='$position',
										sub_cat='$subcat',
										html='$form[html]',
										showonlylogin='$form[showonlylogin]',
										showonlylogout='$form[showonlylogout]' 
									WHERE id='$linkid'");
		}
		unset($form);
		$adminaction	= "menu_edit";
	}
}
#####################
if($adminaction=="save_menu_sort") {
	if($form[lid] && $form[sort]) {$db->query_str("UPDATE $tab[menu] SET sort='$form[sort]' WHERE id='$form[lid]'");}
	$adminaction	= "menu_edit";
}
#####################
if($adminaction=="link_delete") {
	if($form[delete]) {
		foreach($checkbox as $a=>$b) {
			foreach($b as $c=>$d) {
				$delstr = $db->query_str("DELETE FROM $tab[menu] WHERE id='$c' OR sub_cat='$c'");
			}
		}
	}
	$adminaction	= "menu_edit";
}
#####################
if($adminaction=="save_smilies") {
	if(!$db->query("SELECT * FROM $tab[smilie] WHERE stext='$form[stext]'")) {
		$query_str = $db->query_str("INSERT INTO $tab[smilie] (sfile,stext) VALUES ('$form[sfile]','$form[stext]')");
	} else {eval("\$fail_smilies = \"".gettemplate("admin.sworaconfig.smilies.exist")."\";");}
	$adminaction	= "smilies";
}
#####################
if($adminaction=="delete_smilies") {
	if(is_array($smiliecheckbox)) {
		foreach($smiliecheckbox as $a=>$b) {
			$query_str = $db->query_str("DELETE FROM $tab[smilie] WHERE id='$a'");
		}
	}
	$adminaction="smilies";
}
#####################
if($adminaction=="save_section") {
	if($db->query("SELECT * FROM $tab[section] WHERE title='$form[title]'")) {
		eval("\$fail_section = \"".gettemplate("admin.sworaconfig.sections.fail.twice")."\";");
	} else {
		$db->query_str("INSERT INTO $tab[section] (title,name) VALUES ('$form[title]','$form[name]')");
	}
	$adminaction="sections";
}
####################
if($adminaction=="save_edit_section") {
	if(!$form[name] || !$form[title]) {
		eval("\$fail_edit_section = \"".gettemplate("fail.eingabe")."\";");
	} else {
		$db->query_str("UPDATE $tab[section] SET title='$form[title]', name='$form[name]' WHERE id='$secid'");
	}
	$adminaction="sections";
}
####################
if($adminaction=="do_section" || $fail_section) {
	$fail 		= $fail_section;
	$adminaction	= "sections";
	if(is_array($activate)) {
		foreach($activate as $a=>$b) {
			$db->query_str("UPDATE $tab[section] SET active='1' WHERE id='$a'");
		}
	}
	if(is_array($inactivate)) {
		foreach($inactivate as $a=>$b) {
			$db->query_str("UPDATE $tab[section] SET active='0' WHERE id='$a'");
		}
	}
	if($sectionid && $form[delete]) {
		$thissec 	= getsection($sectionid);
		$donotdelete	= FALSE;
		foreach($defaultsections as $s) {if(strtolower($thissec[title])==strtolower($s)) {$donotdelete=TRUE;}}
		if(!$donotdelete) {
			if(!$setupaction) $setupaction	= "uninstall";
			$thispath	= "index.php?section=admin&adminaction=do_section&sectionid=$sectionid&form[delete]=1";
			if(is_file("./sections/$thissec[title]/auto_install.php")) {
				@include("./sections/$thissec[title]/auto_install.php");
				unset($adminaction);
			} else {
				$db->query_str("DELETE FROM $tab[section] WHERE id='$sectionid'");
			}
		} else {
			unset($adminaction);
			$inc[action] = "Dieses Modul kann und darf nicht deinstalliert werden.";
		}
	}
}		
#####################
if($adminaction=="points_save") {
	foreach($form as $name=>$value) {
		$db->query_str("DELETE FROM $tab[config] WHERE name='$name'");
		$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$value','$name')");
	}

	$config = getconfig();
	$adminaction="points";
}
#####################
if($adminaction=="error_delete") {
	if(is_array($checkbox)) {
		foreach($checkbox as $a) {
			$db->query_str("DELETE FROM $tab[error] WHERE id='$a'");
		}
	}
	$adminaction="errors";
}
###########################
if($adminaction=="save_menu_enabled") {
	$db->query_str("DELETE FROM $tab[config] WHERE name='e_menu'");
	$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$form[e_menu]','e_menu')");
	$adminaction="menu_edit";
	$config=getconfig();
}
###########################
if($adminaction=="install_hack") {
	if(is_file("./includes/".$form[hackfile])) {
		if(!$hackaction)$hackaction	= "install";
		$thispath 	= "index.php?section=admin&adminaction=install_hack&form[hackfile]=$form[hackfile]";
		@include("./includes/".$form[hackfile]);
	} else {eval("\$fail_hack = \"".gettemplate("fail.upload.invalidfile")."\";");}
}
###########################
if($adminaction=="config_hack") {
	if(is_file("./includes/".$filename)) {
		$thispath 	= "index.php?section=admin&adminaction=config_hack&filename=$filename";
		@include("./includes/".$filename);
	} else {eval("\$fail_hack = \"".gettemplate("fail.upload.invalidfile")."\";");}
}
###########################
if($adminaction=="uninstall_hack") {
	if($hackfile) {
		if(!$hackaction)$hackaction= "uninstall";
		@include("./includes/".$hackfile);
	}
}
###########################
if($adminaction=="deletetable") {
	if(is_array($deletebox)) {
		foreach($deletebox as $a) {
			if($form[deletetable]) 	$db->query_str("DROP TABLE IF EXISTS $tab[$a]");
						$db->query_str("DELETE FROM $tab[config] WHERE name='tab_$a'");
		}
	}
	$tab 		= gettabs();
	$adminaction 	= "tables";
}
###########################
if($adminaction=="backuptable") {
	if($form[type]==1 || $form[type]==3)
		$dump .= get_table_def($tab[$tablename])."<br>";
	if($form[type]==2 || $form[type]==3)
		$dump .= get_table_content($tab[$tablename]);
	$inc[action] = "<pre>$dump</pre>";
}
###########################
if($adminaction=="renametable") {
	if($form[type]==2) {
		$db->query_str("ALTER TABLE `$tab[$tablename]` RENAME `$form[newname]`");
	}
	if($form[type]==1 || $form[type]==2) {
		$db->query_str("DELETE FROM $tab[config] WHERE name='tab_$tablename'");
		$db->query_str("INSERT INTO $tab[config] VALUES ('tab_$tablename','$form[newname]')");
	}
	$tab 		= gettabs();
	$adminaction	= "edittable";
}
###########################
if($adminaction=="newtable") {
	if($form[tablename] && $form[varname]) {
		if(!$tab[$form[varname]]) {
			$db->query_str("INSERT INTO $tab[config] VALUES ('tab_$form[varname]','$form[tablename]')");
			unset($form);
		} else {eval("\$fail_newtab = \"".gettemplate("fail.twice")."\";");}
	} else {eval("\$fail_newtab = \"".gettemplate("fail.eingabe")."\";");}
	$tab 		= gettabs();
	$adminaction	= "tables";
}
###########################
if($adminaction=="save_htmlcode") {
	foreach($form as $name=>$value) {
		$db->query_str("DELETE FROM $tab[config] WHERE name='$name'");
		$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$value','$name')");
	}
	$adminaction="htmlcode";
	$config = getconfig();
}
###########################
if($adminaction=="save_ftp") {
	$form[path] = str_replace("\\","/",$form[path]);
	if($form[path][strlen($form[path])-1]!="/") 	$form[path].="/";
	if($form[path][0]!="/") 			$form[path] = "/".$form[path];

	if(!$form[host] || !$form[user]) {
		eval("\$fail_ftp = \"".gettemplate("fail.eingabe")."\";");
	} else {
		if(!$ftp->connect($form[host],$form[port],$form[user],$form[pwd])) {
			eval("\$fail_ftp = \"Couldn't Connect or Authenticate on Server\";");
		} else {
			if(!$form[port]) $form[port] = 21;
			if(!$form[path]) $form[path] = "/";
			$db->query_str("INSERT INTO $tab[ftp] (title,host,port,user,pwd,path) VALUES ('$form[title]','$form[host]','$form[port]','$form[user]','$form[pwd]','$form[path]')");
			unset($form);
			$adminaction="ftps";
		}
	}
}
###########################
if($adminaction=="delete_ftp") {
	if(is_array($checkbox)) {
		foreach($checkbox as $a) {
			$db->query_str("DELETE FROM $tab[ftp] WHERE id='$a'");
		}
	}
	$adminaction="ftps";
}
###########################
if($adminaction=="save_edit_ftp") {
	$form[path] = str_replace("\\","/",$form[path]);
	if($form[path][strlen($form[path])-1]!="/") 	$form[path].="/";
	if($form[path][0]!="/") 			$form[path] = "/".$form[path];

	if(!$form[host] || !$form[user]) {
		eval("\$fail_edit_ftp = \"".gettemplate("fail.eingabe")."\";");
	} else {
		if(!$ftp->connect($form[host],$form[port],$form[user],$form[pwd])) {
			eval("\$fail_edit_ftp = \"Couldn't Connect or Authenticate on Server\";");
		} else {
			if(!$form[port]) $form[port] = 21;
			if(!$form[path]) $form[path] = "/";
			$db->query_str("UPDATE $tab[ftp] SET
							title='$form[title]',
							host='$form[host]',
							port='$form[port]',
							user='$form[user]',
							pwd='$form[pwd]',
							path='$form[path]'
							WHERE id='$fid'");
			unset($form);
			$adminaction="ftps";
		}
	}
}
###########################
if($adminaction=="update_eval") {
	if(preg_match("#^update\_(\d{1,3})#i",$form[updatefile],$re)) {
		if(!include("./admin/".$form[updatefile])) {
			eval("\$inc[action] = \"".gettemplate("admin.update.invalidfile")."\";");
		} else {
			$db->query_str("DELETE FROM $tab[config] WHERE name='update_".$re[1]."'");
			$db->query_str("INSERT INTO $tab[config] VALUES ('update_".$re[1]."','".time()."')");
			eval("\$inc[action] .= \"".gettemplate("admin.update.success")."\";");
		}
	}
}
###########################
if($adminaction=="limittraffic_activ") {
	$db->query_str("DELETE FROM $tab[config] WHERE name='limittraffic_activ'");
	if($form[activ]) $db->query_str("INSERT INTO $tab[config] VALUES ('limittraffic_activ','1')");
	$config = getconfig();
	$adminaction="limittraffic";
}
#####################
if($adminaction=="limittraffic_save") {
	if(!is_numeric($form[size]) || !is_numeric($form[time])) {
		eval("\$fail_limittraffic = \"".gettemplate("fail.eingabe")."\";");
	} else {
		$str = $form[size]*$form[sizeb]."::".$form[time]."::".$form[timeb]."::".$form[section];
		$db->query_str("INSERT INTO $tab[config] VALUES ('limit_".$str."','1')");
		unset($form);
	}
	$adminaction="limittraffic";
}
#####################
if($adminaction=="limittraffic_delete") {
	if(is_array($checkbox)) {
		foreach($checkbox as $a)
			$db->query_str("DELETE FROM $tab[config] WHERE name='$a'");
	}
	$adminaction="limittraffic";
}
#####################
if($adminaction=="limittraffic_edit_save") {
	if(!is_numeric($form[size]) || !is_numeric($form[time])) {
		eval("\$fail_limittraffic = \"".gettemplate("fail.eingabe")."\";");
	} else {
		$str = $form[size]*$form[sizeb]."::".$form[time]."::".$form[timeb]."::".$form[section];
		$db->query_str("UPDATE $tab[config] SET name='limit_$str' WHERE name='$lid'");
		unset($form);
	}
	$adminaction="limittraffic";
}


#####################
if($adminaction=="help_new") {
	if($db->query("SELECT * FROM $tab[help] WHERE name='$form[name]'")) {
		eval("\$fail_newhelp = \"".gettemplate("fail.twice")."\";");
	} else {
		if($form[name]=="") {
			eval("\$fail_newhelp = \"".gettemplate("fail.eingabe")."\";");
		} else {
			$db->query_str("INSERT INTO $tab[help] (name,text) VALUES ('$form[name]','$form[text]')");
			unset($form);
		}
	}
	$adminaction="help";
}
#####################
if($adminaction=="help_delete") {
	if(is_array($deletebox)) {
		foreach($deletebox as $a=>$b) {
			$db->query_str("DELETE FROM $tab[help] WHERE id='$a'");
		}
	}
	$adminaction = "help";
}
#####################
if($adminaction == "help_save") {
	if($form[name]=="") {
		eval("\$fail_edithelp = \"".gettemplate("fail.eingabe")."\";");
	} else {
		$db->query_str("UPDATE $tab[help] SET name='$form[name]',text='$form[text]' WHERE id='$hid'");
		$adminaction="help";
		unset($form);
	}
}
#####################





///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################
///////////////////////////////////////////////////////////////////
###################################################################





#####################
if($adminaction=="help_edit" || $fail_edithelp) {
	$fail = $fail_edithelp;
	if(!$form && !$fail) {
		$form = $db->query("SELECT * FROM $tab[help] WHERE id='$hid'");
	}
	eval("\$inc[action] = \"".gettemplate("admin.help.edit")."\";");
}
#####################
if($adminaction=="help" || $fail_newhelp) {
	$fail = $fail_newhelp;
	$i=0;
	if(!$show) $show = 40;
	if(!$start) $start = 0;
	$LIMIT = "$start,$show";
	$q = $db->query_str("SELECT * FROM $tab[help] ORDER BY id ASC LIMIT $show");
	while($help = $db->fetch_array($q)) {
		if($i++%5 == 0)eval("\$helpbits .= \"".gettemplate("admin.help.bit.tr")."\";");
		eval("\$helpbits .= \"".gettemplate("admin.help.bit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("admin.help.main")."\";");
}

#####################
if($adminaction=="traffic") {
	$sum 		= $db->query("SELECT SUM(traffic) FROM $tab[traffic]");$sum=$sum[0];

	if(!$start) 	$start = 0;
	if(!$show)	$show = 25;
	$LIMIT 		= " LIMIT $start,$show";

	$query 		= $db->query_str("SELECT * FROM $tab[traffic] ORDER BY date DESC$LIMIT");
	while($week 	= $db->fetch_array($query)) {
	 	$traff 			= formatfsize($week[traffic]);
	  	$timestamp_start 	= $week[date];
	 	$day 			= mkdate($timestamp_start,"d.m.Y");
	 	$graph 			= ceil(($week[traffic]/$sum)*100);
	 	eval("\$trafficbits .= \"".gettemplate("admin.sworaconfig.traffic.weekbit")."\";");
	}
	list($count)		= $db->query("SELECT COUNT(*) FROM $tab[traffic]");
	$pages			= mkpages($count,$show,"admin.sworaconfig.traffic.pages.bit","admin.sworaconfig.traffic.pages");

	list($sum_alltraffic)	= $db->query("SELECT SUM(traffic) FROM $tab[traffic]");
	list($sum_pday) 	= $db->query("SELECT SUM(traffic)/COUNT(*) FROM $tab[traffic]");

	$sizeof_alltraffic 	= formatfsize($sum_alltraffic);
	$sizeof_perday 	= 	formatfsize($sum_pday);

	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.traffic.main")."\";");
}
#####################
if($adminaction=="auto_install_start") {
	if(is_file("./sections/".$sectioninstall."/auto_install.php")) {
		$thispath = "index.php?section=admin&adminaction=auto_install_start&sectioninstall=$sectioninstall";
		@include("./sections/".$sectioninstall."/auto_install.php");
	}
	$isinstalled = $sectoinstall;
}
###########################
if($adminaction=="mainconfig") {
	$form 			= getconfig();
	if($form[stylesetstatic]) $selectstylesetstatic = " checked"; else $selectstylesetstatic = "";
	$ftpq			= $db->query_str("SELECT * FROM $tab[ftp]");
	while($server=$db->fetch_array($ftpq)) {
		if($config[avatar_ftpid]==$server[id]) $selected=" selected"; else unset($selected);
		eval("\$ftplist .= \"".gettemplate("admin.sworaconfig.ftp.optionbit")."\";");
	}
	$dir = @dir($style[templates]);
	while($f = $dir->read()) {
		if($f[0]!=".") {
			if($config[defaultstyleset]==$f) $selected = " selected"; else $selected="";
			$styles .= "<option value=\"$f\"$selected>$f</option>";
		}
	}
	$q = $db->query_str("SELECT * FROM $tab[section] WHERE active='1'");
	while($sect = $db->fetch_array($q)) {
		if($config[startsection]==$sect[id]) $selected = " selected"; else $selected="";
		$secoption .= "<option value=\"$sect[id]\"$selected>$sect[title]</option>";
	}
	$use_smtp_checkbox 	= ($form[smtp_use]) ? " checked" : "";
	eval("\$inc[action] 	= \"".gettemplate("admin.sworaconfig.main")."\";");
}
###########################
if($adminaction=="menu_new" || $fail_new_menu) {
	$fail 		= $fail_new_menu;
	$subquery 	= $db->query_str("SELECT * FROM $tab[menu] WHERE mode='cat' ORDER BY title");
	while($cat 	= $db->fetch_array($subquery)) {
		eval("\$subcats .= \"".gettemplate("admin.sworaconfig.menu.catsbit")."\";");
	}
	if(!$form[href]) $form[href] = "index.php?section=??";
	$newedit 		= "menu_new_save";
	eval("\$inc[action] 	= \"".gettemplate("admin.sworaconfig.menu.main")."\";");
}
#####################
if($adminaction=="link_edit" || $fail_link_edit) {
	$newedit 	= "menu_edit_save";
	$form 		= $db->query("SELECT * FROM $tab[menu] WHERE id='$linkid'");
	$subquery 	= $db->query_str("SELECT * FROM $tab[menu] WHERE mode='cat' ORDER BY title");
	while($cat 	= $db->fetch_array($subquery)) {
		if($form[sub_cat]==$cat[id]) $select = " selected"; else unset($select);
		eval("\$subcats .= \"".gettemplate("admin.sworaconfig.menu.catsbit")."\";");
	}

	$v = $form[mode]."select";$$v = " selected";

	if(!$form[position])		{$topselect	= " selected";}
	elseif($form[position]=="1") 	{$leftselect 	= " selected";}
	elseif($form[position]=="2")	{$rightselect 	= " selected";}
	elseif($form[position]=="3")	{$bottomselect 	= " selected";}

	if($form[showonlylogin])	{$loginselect 	= " checked";}
	if($form[showonlylogout])	{$logoutselect 	= " checked";}
	$form[href] = 			$form[link];

	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.menu.main")."\";");
}
###########################
if($adminaction=="menu_edit" || $fail_menu_edit) {
	global $config;
	$fail 		= $fail_menu_edit;
	$mquery 	= $db->query_str("SELECT * FROM $tab[menu] WHERE sub_cat='0' ORDER BY sort");
	while($cat	= $db->fetch_array($mquery)) {
		//////////////
		// STYLE && SELECT
		//////////////

		for($i=0;$i<=15;$i++) {unset(${"c$i"});}unset($m);
		$m = "c".$cat[sort];$$m = " selected";

		$mquerylink 	= $db->query_str("SELECT * FROM $tab[menu] WHERE (mode='link' OR mode='html') AND sub_cat='$cat[id]' ORDER BY sort");
		while($link 	= $db->fetch_array($mquerylink)) {

			if($link[showonlylogin]) 				{$bgstyle="menu_members_members";}
			if($link[showonlylogout]) 				{$bgstyle="menu_members_nonmembers";}
			if(!$link[showonlylogout] && !$link[showonlylogin])	{$bgstyle="menu_members_all";}

			if($cat[showonlylogin])					{$bgstyle="menu_members_members";}
			if($cat[showonlylogout])				{$bgstyle="menu_members_nonmembers";}


			for($i=0;$i<=15;$i++) { unset(${"l$i"}); }unset($s);
			$s = "l".$link[sort];$$s = " selected";
			if($link[mode]=="html")		{eval("\$links.= \"".gettemplate("admin.sworaconfig.menu.list.subcat.html")."\";");}
			if($link[mode]=="link")		{eval("\$links.= \"".gettemplate("admin.sworaconfig.menu.list.subcat.link")."\";");}
		}

		if(!$cat[showonlylogin] && !$cat[showonlylogout]) 	{$bgstyle="menu_members_all";}
		elseif($cat[showonlylogout]) 				{$bgstyle="menu_members_nonmembers";}
		else 							{$bgstyle="menu_members_members";}

		// CATS ------------------------//
		if(!$cat[position]) {
			if($cat[mode]=="link")		{eval("\$inc[top].= \"".gettemplate("admin.sworaconfig.menu.list.cat.link")."\";");}
			if($cat[mode]=="html")		{eval("\$inc[top].= \"".gettemplate("admin.sworaconfig.menu.list.cat.html")."\";");}
			if($cat[mode]=="cat")		{eval("\$inc[top].= \"".gettemplate("admin.sworaconfig.menu.list.cat.th")."\";");}
			if($cat[mode]=="script")	{eval("\$inc[top].= \"".gettemplate("admin.sworaconfig.menu.list.cat.script")."\";");}
		}
		if($cat[position]=="1") {
			if($cat[mode]=="link")		{eval("\$inc[left].= \"".gettemplate("admin.sworaconfig.menu.list.cat.link")."\";");}
			if($cat[mode]=="html")		{eval("\$inc[left].= \"".gettemplate("admin.sworaconfig.menu.list.cat.html")."\";");}
			if($cat[mode]=="cat")		{eval("\$inc[left].= \"".gettemplate("admin.sworaconfig.menu.list.cat.th")."\";");}
			if($cat[mode]=="script")	{eval("\$inc[left].= \"".gettemplate("admin.sworaconfig.menu.list.cat.script")."\";");}
		}
		elseif($cat[position]=="2") {
			if($cat[mode]=="link")		{eval("\$inc[right].= \"".gettemplate("admin.sworaconfig.menu.list.cat.link")."\";");}
			if($cat[mode]=="html")		{eval("\$inc[right].= \"".gettemplate("admin.sworaconfig.menu.list.cat.html")."\";");}
			if($cat[mode]=="cat")		{eval("\$inc[right].= \"".gettemplate("admin.sworaconfig.menu.list.cat.th")."\";");}
			if($cat[mode]=="script")	{eval("\$inc[right].= \"".gettemplate("admin.sworaconfig.menu.list.cat.script")."\";");}
		}
		elseif($cat[position]=="3") {
			if($cat[mode]=="link")		{eval("\$inc[bottom].= \"".gettemplate("admin.sworaconfig.menu.list.cat.link")."\";");}
			if($cat[mode]=="html")		{eval("\$inc[bottom].= \"".gettemplate("admin.sworaconfig.menu.list.cat.html")."\";");}
			if($cat[mode]=="cat")		{eval("\$inc[bottom].= \"".gettemplate("admin.sworaconfig.menu.list.cat.th")."\";");}
			if($cat[mode]=="script")	{eval("\$inc[bottom].= \"".gettemplate("admin.sworaconfig.menu.list.cat.script")."\";");}
		}
		unset($links);
	}
	if($config[e_menu]) {$emenuselecton=" checked";} else {$emenuselectoff=" checked";}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.menu.list")."\";");
}
###########################
if($adminaction=="smilies" || $fail_smilies) {
	$fail 		= $fail_smilies;
	$squery 	= $db->query_str("SELECT * FROM $tab[smilie]");
	while($s 	= $db->fetch_array($squery)) {
		eval("\$setsmilies .= \"".gettemplate("admin.sworaconfig.smilies.setsmilies.bit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.smilies")."\";");
}
###########################
if($adminaction=="sections" || $fail_section) {
	$fail 		= $fail_section;
	$query 		= $db->query_str("SELECT * FROM $tab[section] ORDER BY id DESC");
	while($se 	= $db->fetch_array($query)) {
		$modulversion	= sprintf("%.1f",$version[$se[title]]);
		unset($activate);
		if(!$se[active]) 				{eval("\$do 	= \"".gettemplate("admin.sworaconfig.section.activesubmit")."\";");}
		else 						{eval("\$do 	= \"".gettemplate("admin.sworaconfig.section.inactivesubmit")."\";");}
		if(!is_dir("./sections/".$se[title]."/"))	{eval("\$do 	= \"".gettemplate("admin.sworaconfig.section.invalidsubmit")."\";");}
		eval("\$sections .= \"".gettemplate("admin.sworaconfig.sections.bit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.sections")."\";");
}
###########################
if($adminaction=="section_edit" || $fail_edit_section) {
	$fail = $fail_editsection;
	$section = getsection($secid);
	foreach($defaultsections as $s) {if(strtolower($section[title])==strtolower($s)) {$donotedit=TRUE;}}
	if($donotedit)	eval("\$inc[action] = \"This section cannot be edited.\";");
	else 		eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.section.edit")."\";");
}
###########################
if($adminaction=="errors") {
	if(!$start)	$start = 0;
	if(!$show)	$show = 10;
	$LIMIT = " LIMIT $start,$show";
	$errorsq = $db->query_str("SELECT * FROM $tab[error] ORDER BY id ASC$LIMIT");
	if(!mysql_num_rows($errorsq)) {
		eval("\$errorbits = \"".gettemplate("admin.sworaconfig.errors.non")."\";");
	} else {
		while($error = $db->fetch_array($errorsq)) {
			if(strlen($error[text])>90) {
				eval("\$textplus = \"".gettemplate("admin.sworaconfig.errors.morelink")."\";");
				$error[text] 		= substr($error[text],0,90).$textplus;
			}
			$error[request_uri] 	= mk2url($error[request_uri]);
			eval("\$errorbits .= \"".gettemplate("admin.sworaconfig.errors.errorbit")."\";");
		}
		list($count) = $db->query("SELECT COUNT(*) FROM $tab[error]");
		$page = mkpages($count,$show,"admin.sworaconfig.errors.pagebit");
	}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.errors.main")."\";");
}
###########################
if($adminaction=="showerror") {
	if($error=$db->query("SELECT * FROM $tab[error] WHERE id='$eid'")) {
		$name = basename($error[scriptname]);
		$error[text] = str_replace("\n","<br>",$error[text]);
		eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.errors.show")."\";");
	} else {header("LOCATION: index.php?section=admin&adminaction=errors");}
}
#####################
if($adminaction=="points") {
	$dir = dir($style[templatefolder]."/admin");
	while($f = $dir->read()) {
		if(preg_match("#admin.sworaconfig.points#",$f) && $f!="admin.sworaconfig.points.main.html") {
			eval("\$otherpoints.=\"".gettemplate(substr($f,0,-5))."\";");
		}
	}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.points.main")."\";");
}
#####################
if($adminaction=="new_hack" || $fail_hack) {
	if($fail_hack) {$fail=$fail_hack;}
	$dir 		= dir("./includes/");
	while($f 	= $dir->read()) {
		if(preg_match("#e_#",$f)) {
			$filename = $f;
			$hackaction="isinstalled";
			@include("./includes/".$f);
			if($isinstalled)eval("\$hacklist .= \"".gettemplate("admin.sworaconfig.hack.bit")."\";");
		}
	}
	if (!$hacklist)eval("\$hacklist .= \"".gettemplate("admin.sworaconfig.hack.nobit")."\";");
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.hack.main")."\";");
}
#####################
if($adminaction=="tables") {
	$fail = $fail_newtab;
	foreach($tab as $tablename=>$tablevalue) {
		if($tablename=="config") continue;
		eval("\$tablebits .= \"".gettemplate("admin.sworaconfig.tables.bit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.tables.main")."\";");
}
#####################
if($adminaction=="edittable") {
	if($tab[$tablename]) {
		$table = getdbtablestatus($tab[$tablename]);
		if(!$table[Name]) $table[Name] = "Invalid";
		eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.tables.edit.main")."\";");
	}
}
#####################
if($adminaction=="htmlcode") {
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.htmlcode.main")."\";");
}
#####################
if($adminaction=="bannes") {
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.bannes")."\";");
}
#####################
if($adminaction=="ftps" || $fail_ftp) {
	$fail = $fail_ftp;
	$ftpquery = $db->query_str("SELECT * FROM $tab[ftp] ORDER BY id ASC");
	while($server =$db->fetch_array($ftpquery)) {
		eval("\$ftplist .= \"".gettemplate("admin.sworaconfig.ftps.bit")."\";");
	}
	if(!$form[port])$form[port]=21;
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.ftps.main")."\";");
}
#####################
if($adminaction=="edit_ftp" || $fail_edit_ftp) {
	$fail = $fail_edit_ftp;
	if(!$form)	$form = getftpaccesscodes($fid);
	if(!$form[port])$form[port]=21;
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.ftps.edit")."\";");
}
#####################//
if($adminaction=="update") {
	foreach($config as $a=>$b) {
		if(preg_match("#^version\_(.*)#i",$a,$re)) {
			$version[$re[1]] = (double)$b;
	}	}
	$installed = Array();
	$dir = dir("./admin");
	while($f=$dir->read()) {
		if($f[0]==".") continue;
		if(preg_match("#^update\_(\d{1,3})#i",$f,$re)) {
			$file = file("./admin/".$f);$info = substr($file[1],2);
			@include("./admin/".$f);
			if($moduleisinstalled==TRUE)
				{$installed[] = Array("file"=>$f,"name"=>$name,"date"=>"$value","info"=>$info);}
			else 	{$notinstalled[] = Array("file"=>$f,"info"=>$info);}
		}
	}
	if($installed) {
		foreach($installed as $a)    	{$date=mkdate($a[date]);eval("\$installedt .= \"".gettemplate("admin.update.installed.bit")."\";");}
	} else {eval("\$installedt = \"".gettemplate("admin.update.noupdate")."\";");}
	if($notinstalled) {
		foreach($notinstalled as $a) 	{eval("\$notinstalledt .= \"".gettemplate("admin.update.notinstalled.bit")."\";");}
	} else {eval("\$notinstalledt = \"".gettemplate("admin.update.noupdate")."\";");}
	eval("\$inc[action] = \"".gettemplate("admin.update")."\";");
}
#####################
if($adminaction=="limittraffic_edit" || $fail_limittraffic_edit) {
	$fail = $fail_limittraffic_edit;
	$form=$db->query("SELECT * FROM $tab[config] WHERE name='$lid'");
	if(!$form) {$adminaction="limittraffic";}
	else {
		preg_match("#limit_(\d+)::(\d+)::(\w+)::(\d+)#i",$form[name],$re);
		$q = $db->query_str("SELECT  id,title FROM $tab[section] WHERE active='1'");
		while($result=$db->fetch_array($q)) {
			if($re[4]==0) $allselected = " selected";
			if($re[4]==$result[id]) $selected = " selected"; else $selected = "";
			$select[$re[3]]=" selected";
			$sectionbits .= "<option value=\"$result[id]\"$selected>$result[title]";
		}
		eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.limittraffic.edit")."\";");
	}
}
#####################
if($adminaction=="limittraffic" || $fail_limittraffic) {
	$fail = $fail_limittraffic;
	if(!$config[limittraffic_activ]) $off = " checked"; else $on = " checked";
	$q = $db->query_str("SELECT  id,title FROM $tab[section] WHERE active='1'");
	while($result=$db->fetch_array($q)) $sectionbits .= "<option value=\"$result[id]\">$result[title]";
	$q = $db->query_str("SELECT * FROM $tab[config] WHERE name LIKE 'limit\_%'");
	while($result = $db->fetch_array($q)) {
		preg_match("#limit_(\d+)::(\d+)::(\w+)::(\d+)#i",$result[name],$re);
		$size = formatfsize($re[1]);
		if($re[4]==0) {$secname[title] = "All";}
		else {$secname = getsection($re[4]);}
		eval("\$limitlist.=\"".gettemplate("admin.sworaconfig.limittraffic.bit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("admin.sworaconfig.limittraffic")."\";");
}
?>
