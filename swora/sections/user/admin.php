<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

#######################################
if($adminaction=="newuser_save") {
	### check entrys ####
	if(!$form[user_login] || !$form[user_name] || !$form[reg_email]) {
		eval("\$fail_newuser = \"".gettemplate("fail.eingabe")."\";");
	}
	elseif(!checkmail($form[reg_email])) {
		eval("\$fail_newuser =\"".gettemplate("fail.eingabe.invalid.mail")."\";");
	}
		### check username ###
	elseif($checkuser = $db->query("SELECT * FROM $tab[user] WHERE user_login='$form[user_login]'") && !$insertid) {
		eval("\$fail_newuser = \"".gettemplate("fail.user.twice")."\";");
	} else {
		$query = $db->query_str("INSERT INTO $tab[user] (user_login,user_name,user_email,user_location,reg_email,reg_date,activated,logins,uin) 
					VALUES ('$form[user_login]','$form[user_name]','$form[reg_email]','$form[user_location]','$form[reg_email]','$date','1','0','".session_id()."')");
		$insertid=mysql_insert_id();
		### MAKE BIRTHDAY ###
		$birthstamp = mktime(0,0,0,$form[birthmonth],$form[birthday],$form[birthyear]);
		### SAVE QUERY ###
		$date = time();
		$query = $db->query_str("UPDATE $tab[user] SET
				user_password='".md5($form[user_password])."',
				user_icq='$form[user_icq]',
				user_aim='$form[user_aim]',
				user_yim='$form[user_yim]',
				user_hp='$form[user_hp]',
				user_interests='$form[user_interests]',
				user_signatur='$form[user_signatur]',
				user_text='$form[user_text]',
				user_birth='$birthstamp',
				user_work='$form[user_work]',
				user_gender='$form[user_gender]',
				show_email='1',
				allow_pm='1'
				WHERE id='$insertid'");
		if($query) {
			eval("\$inc[action] = \"".gettemplate("user.admin.finischednewuser")."\";");
		}
	}
}
#######################################
if($adminaction=="newsletter_send") {
	$userq = $db->query_str("SELECT * FROM $tab[useroption] WHERE name='newsletter' AND value='1'");
	while($userid = $db->fetch_array($userq)) {
		$user = getuser($userid[userid]);
		unset($form_body,$user_name,$form_subject);

		eval("\$mail_subject =  \"".gettemplate("user.admin.newsletter.mail.subject")."\";");
		eval("\$mail_body = 	\"".gettemplate("user.admin.newsletter.mail.body")."\";");
		eval("\$mail_header = 	\"".gettemplate("user.admin.newsletter.mail.header")."\";");

		$mail_body = stripslashes($mail_body);

		if(!$user[user_email]) $mail = $user[reg_email];
		else $mail = $user[user_email];

		if($user[reg_email]) {$sendmail->mail($email,stripslashes($mail_subject),stripslashes($mail_body),$mail_header);}
	}
	eval("\$fail_newsletter = \"".gettemplate("admin.done")."\";");
}
#######################################
if($adminaction=="edituser_save") {
	### check entrys ####
	if(!$form[user_login] || !$form[user_name] || !$form[reg_email]) {
		eval("\$fail_useredit = \"".gettemplate("fail.eingabe")."\";");
	}
	elseif(!checkmail($form[reg_email])) {
		eval("\$fail_useredit =\"".gettemplate("fail.eingabe.invalid.mail")."\";");
	} else {
		if($form[delete]) {
			if($userid!=1) {
				$db->query_str("DELETE FROM $tab[user] WHERE id='$userid'");
			} else {
				$inc[action] = "GastAccount cannot be deleted.";
			}
		} else {
			### SAVE QUERY ###
			$date = time();
			$query = $db->query_str("UPDATE $tab[user] SET 
					user_login='$form[user_login]',
					user_name='$form[user_name]',
					user_email='$form[user_email]',
					user_location='$form[user_location]',
					reg_email='$form[reg_email]',
					user_icq='$form[user_icq]',
					user_aim='$form[user_aim]',
					user_yim='$form[user_yim]',
					user_hp='$form[user_hp]',
					user_interests='$form[user_interests]',
					user_signatur='$form[user_signatur]',
					user_text='$form[user_text]',
					user_work='$form[user_work]',
					user_gender='$form[user_gender]'
					WHERE id='$userid'");
		}
		$adminaction="showusers";
	}
}
#######################################
if($adminaction=="new_blockinactivate_save") {
	if($form[userid]) {
		if($user = getuser($form[userid])) {
			$db->query_str("UPDATE $tab[user] SET blocked='1' WHERE id='$form[userid]'");
			eval("\$mailbody = \"".gettemplate("user.admin.mail.block.body")."\";");
			eval("\$mailsubject = \"".gettemplate("user.admin.mail.block.subject")."\";");
			eval("\$mailheader = \"".gettemplate("user.admin.mail.block.header")."\";");
			$sendmail->mail($user[reg_email],$mailsubject,$mailbody,$mailheader);
			header("LOCATION: $HTTP_REFERER");
		} else {
			$block_fail = "no valid user";
		}
	}
}
#######################################
if($adminaction=="rights_save") {
	if(is_allowed($form[sectionid],$form[boardid],$form[userid])) {
		eval("\$fail_rights = \"".gettemplate("user.admin.rights.fail.ismod")."\";");
	} else {
		if($form[id_admin]) $form[is_admin] = "1";
		$querystr = $db->query_str("INSERT INTO $tab[right] (userid,section,boardid,is_admin,activ,settime) VALUES ('$form[userid]','$form[sectionid]','$form[boardid]','$form[is_admin]','1','".time()."')");
		$adminaction="rights";
	}
}
#######################################
if($adminaction=="rights_delete") {
	if($deletemod) {
		foreach($deletemod as $a)
			$delstr = $db->query_str("DELETE FROM $tab[right] WHERE id='$a'");
	}
	$adminaction="rights";
}
#######################################
if($adminaction=="blockinactiv_save") {
	if($activate) {
		foreach($activate as $a=>$b) {
			$db->query_str("UPDATE $tab[user] SET activated='1' WHERE id='$a'");

			$user = getuser($a);
			eval("\$mailbody = \"".gettemplate("user.admin.mail.activation.body")."\";");
			eval("\$mailsubject = \"".gettemplate("user.admin.mail.activation.subject")."\";");
			eval("\$mailheader = \"".gettemplate("user.admin.mail.activation.header")."\";");

			$sendmail->mail($user[reg_email],$mailsubject,$mailbody,$mailheader);
		}
	}
	elseif($unblock) {
		foreach($unblock as $a=>$b) {
			$db->query_str("UPDATE $tab[user] SET blocked='0' WHERE id='$a'");

			$user = getuser($a);
			eval("\$mailbody = \"".gettemplate("user.admin.mail.unblock.body")."\";");
			eval("\$mailsubject = \"".gettemplate("user.admin.mail.unblock.subject")."\";");
			eval("\$mailheader = \"".gettemplate("user.admin.mail.unblock.header")."\";");

			$sendmail->mail($user[reg_email],$mailsubject,$mailbody,$mailheader);
		}
	}
	$adminaction="blockinactiv";
}

#######################################
################################################################################
################################################################################
#######################################
if($adminaction=="rights" || $fail_rights) {
	$fail = $fail_rights;
	$userquery = $db->query_str("SELECT * FROM $tab[user] ORDER BY id ASC");
	while($user = $db->fetch_array($userquery)) {
		if($user[id]=="1") continue;
		eval("\$users .= \"".gettemplate("user.admin.rights.users.bit")."\";");
	}
	$secquery = $db->query_str("SELECT * FROM $tab[section]");
	while($section = $db->fetch_array($secquery)) {
		eval("\$sections .= \"".gettemplate("user.admin.rights.sections.bit")."\";");
	}
	if($tab[forum_board]) {
		$boardq = $db->query_str("SELECT * FROM $tab[forum_board] ORDER BY id ASC");
		while($board = $db->fetch_array($boardq)) {
			eval("\$boards .= \"".gettemplate("forum.admin.rights.board.bits")."\";");
		}
	}

	$modsquery = $db->query_str("SELECT * FROM $tab[right] ORDER BY is_admin DESC");
	while($right = $db->fetch_array($modsquery)) {
		$user = getuser($right[userid]);

		if(!$right[is_admin]) {
			$thesection = getsection($right[section]);
			if(!$thesection) $thesection[title]="invalid";
		} else 	$thesection[title] = "-----";


		if($right[boardid]=="0" && $right[section]==$sec[forum][id]) {
			$board = "<i>ALL BOARDS</i>";
		} elseif($right[boardid]) {
			$board = getboard($right[boardid]);$board = $board[board_name];
		} else {
			$board = "-----";
		}

		eval("\$moderators .= \"".gettemplate("user.admin.rights.listmoderators.bit")."\";");
	}

	eval("\$inc[action] = \"".gettemplate("user.admin.rights")."\";");
}
#######################################
if($adminaction=="showuser" || $fail_useredit) {
	$fail = $fail_useredit;
	$form = getuser($userid);
	($form[user_gender]-2) ? $male=" selected" : $female=" selected";
	$birthday = mkdate($form[user_birth],"d-m-Y");
	eval("\$inc[action] = \"".gettemplate("user.admin.edituser")."\";");
}
#######################################
if($adminaction=="showusers") {
	if(strtolower($sort)!="desc" 		&& strtolower($sort)!="asc")	$sort 	= "ASC";
	if(strtolower($order)!="user_name" 	&& strtolower($order)!="id")	$order 	= "id";
	if(!$start || !is_numeric($start))	$start 	= 0;
	if(!$show  || !is_numeric($show))	$show 	= 20;
	$LIMIT = " LIMIT $start,$show";
	$userquery = $db->query_str("SELECT * FROM $tab[user] WHERE activated='1' AND blocked='0' AND id<>'1' ORDER BY $order $sort$LIMIT");
	while($user = $db->fetch_array($userquery)) {
		eval("\$inc[users] .= \"".gettemplate("user.admin.list.user.bit")."\";");
	}
	list($count) = $db->query("SELECT COUNT(*) FROM $tab[user] WHERE activated='1' AND blocked='0' AND id<>'1'");
	$pages = mkpages($count,$show,"user.admin.list.user.pages.bit");
	eval("\$inc[action] = \"".gettemplate("user.admin.list.user")."\";");
}
#######################################
if($adminaction=="blockinactiv" || $block_fail) {
	$fail = $block_fail;
	$aquery = $db->query_str("SELECT * FROM $tab[user] WHERE activated='0' OR blocked='1' ORDER BY activated DESC");
	if(!mysql_num_rows($aquery)) {
		eval("\$inc[users] = \"".gettemplate("user.admin.list.blockinacitvate.nowaiting")."\";");
	} else {
		while($user = $db->fetch_array($aquery)) {
			if(!$user[activated])
				$step = "Anmeldung <b>nicht</b> abgeschlossen";
			else 	$step = "Anmeldung abgeschlossen";
			// ---------//
			if($user[activated]) 	$activated="";
			else 			eval("\$activated=\"".gettemplate("user.admin.blockinactivate.submit.activated")."\";");
			if(!$user[blocked]) 	$unblock="";
			else 			eval("\$unblock=\"".gettemplate("user.admin.blockinactivate.submit.unblock")."\";");

			eval("\$inc[users] .= \"".gettemplate("user.admin.list.blockinacitvate.bit")."\";");
		}
	}
	eval("\$inc[action] = \"".gettemplate("user.admin.list.blockinacitvate")."\";");
}
#######################################
if($adminaction=="newsletter" || $fail_newsletter) {
	$fail = $fail_newsletter;
	eval("\$inc[action] = \"".gettemplate("user.admin.newsletter.main")."\";");
}
#######################################
if($adminaction=="newuser" || $fail_newuser) {
	$fail = $fail_newuser;
	eval("\$inc[action]=\"".gettemplate("user.admin.newuser")."\";");
}
#######################################
?>
