<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

if($action) {
	// SAVES
	// ------------------------------
	if($action=="createnewaccount") {
			### CHECK ENTRYS ###
		if(!$form[user_login] || !$form[user_name] || !$form[reg_email] || !$form[user_location]) {
			eval("\$fail 	= \"".gettemplate("fail.eingabe")."\";");
		}
		elseif(!$form[user_work] || !$form[user_email] || !$form[birthmonth] || !$form[birthday] || !$form[birthyear] || strlen($form[birthyear])<=2 || !$form[user_gender]) {
			eval("\$fail 	= \"".gettemplate("fail.eingabe")."\";");
		}
		elseif(!checkmail($form[user_email])) {
			eval("\$fail	= \"".gettemplate("fail.eingabe.invalid.mail")."\";");
		}
		elseif(!checkmail($form[reg_email])) {
			eval("\$fail	= \"".gettemplate("fail.eingabe.invalid.mail")."\";");
		}
			### check username ###
		elseif($checkuser 	= $db->query("SELECT * FROM $tab[user] WHERE user_login='$form[user_login]' || user_name='$form[user_login]'") && !$insertid) {
			eval("\$fail 	= \"".gettemplate("fail.user.twice")."\";");
		}
			### CHECK USER BANNED ###
		elseif(preg_match("/$form[user_login]/i",$config[ban_name])) {
			eval("\$fail 	= \"".gettemplate("fail.user.bannedname")."\";");
		}
		elseif(preg_match("/$form[reg_email]/i",$config[ban_email]) ) {
			eval("\$fail 	= \"".gettemplate("fail.user.bannedmail")."\";");
		}
		elseif(preg_match("/$form[user_email]/i",$config[ban_email]) ) {
			$reged 		= $db->query("SELECT * FROM $tab[user] WHERE id='$userinsertid'");
			eval("\$fail 	= \"".gettemplate("fail.user.bannedmail")."\";");
		}
			### SHOW FAILS ###
		if($fail) {
			eval("\$inc[action] = \"".gettemplate("user.newuser.form")."\";");
		} else {
			### SAVE QUERY ###
			$date 		= time();
			$query 		= $db->query_str("INSERT INTO $tab[user] (user_login,user_name,user_email,user_location,reg_email,reg_date,activated,logins,uin) 
							VALUES ('$form[user_login]','$form[user_name]','$form[reg_email]','$form[user_location]','$form[reg_email]','$date','0','0','".session_id()."')");

			$userinsertid	= mysql_insert_id();
			$reged 		= $db->query("SELECT * FROM $tab[user] WHERE id='$userinsertid'");
			$user_password 	= generatepass(10);
			$actcode 	= generatepass(25);
			$birthstamp 	= mktime(0,0,0,$form[birthmonth],$form[birthday],$form[birthyear]);
			### SAVE QUERY ###
			$date 		= time();
			$query		= $db->query_str("UPDATE $tab[user] SET 
						user_email='$form[user_email]',
						user_password='".md5($user_password)."',
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
						activation_code='$actcode',
						md5='1'
						WHERE id='$userinsertid'");

			$query 	       = $db->query_str("INSERT INTO $tab[useroption] (userid,name,value) VALUES ('$userinsertid','show_email','1')");
			$query 	       = $db->query_str("INSERT INTO $tab[useroption] (userid,name,value) VALUES ('$userinsertid','allow_pm','1')");

			$activationurl  	= $httpurl."/index.php?section=user&action=activate&userid=$userinsertid&code=$actcode";

			eval("\$mailsubject 	= \"".gettemplate("user.newuser.mailsubject")."\";");
			eval("\$mailbody 	= \"".gettemplate("user.newuser.mailbody")."\";");
			eval("\$mailheader 	= \"".gettemplate("user.newuser.mailheader")."\";");
		/*
		 *	eval("\$mailsubjectad 	= \"".gettemplate("user.newuser.admin.mailsubject")."\";");
		 *	eval("\$mailbodyad 	= \"".gettemplate("user.newuser.admin.mailbody")."\";");
		 *	eval("\$mailheaderad 	= \"".gettemplate("user.newuser.admin.mailheader")."\";");
		 */
		/*	### INFORM ADMINS ###
		 *	$adminquery 		= $db->query_str("SELECT * FROM $tab[right] WHERE is_admin='1'");
		 *	while($adminright 	= $db->fetch_array($adminquery)) {
		 *		$admin 		= getuser($adminright[userid]);
		 *		$sendmail->mail($admin[user_email], $mailsubjectad, $mailbodyad, $mailheaderad);
		 *	}
		 */
			### SEND PASSWORD TO USER ###
			$sendmail->mail($reged[reg_email], $mailsubject, $mailbody, $mailheader);
			if($query) {
				eval("\$inc[action] = \"".gettemplate("user.newuser.finisched")."\";");
			}
		}
	}
	##########################################
	if($action=="activate") {
		if($code && $userid) {
			$user = getuser($userid);
			if($user[activation_code]==$code) {
				$db->query_str("UPDATE $tab[user] SET activated='1' WHERE id='$userid'");
				eval("\$inc[action] 	= \"".gettemplate("user.activated")."\";");
			} else {eval("\$inc[action] 	= \"".gettemplate("user.fail.activated")."\";");}
		} else {eval("\$inc[action] = \"".gettemplate("fail.eingabe")."\";");}
	}
	##########################################
	if($action=="sendpasswordkey") {
		if(!checkmail($form[reg_email])) {eval("\$fail_forgotnpwd = \"".gettemplate("fail.eingabe.invalid.mail")."\";");}
		else {
			$user = $db->query("SELECT * FROM $tab[user] WHERE reg_email='$form[reg_email]' OR user_email='$form[reg_email]'LIMIT 1");
			if(!$user[id] || $user[id]==1) {eval("\$fail_forgotnpwd = \"".gettemplate("user.forgotpassword.step1.nouser")."\";");}
			else {
				$key 	= generatepass(25);

				### SQL ###
				$db->query_str("UPDATE $tab[user] SET lostpassword='$key' WHERE id='$user[id]'");
				### Mail ###
				eval("\$mail[subject] 	= \"".gettemplate("user.forgotpassword.mail.subject")."\";");
				eval("\$mail[body] 	= \"".gettemplate("user.forgotpassword.mail.body")."\";");
				eval("\$mail[header] 	= \"".gettemplate("user.forgotpassword.mail.header")."\";");

				$sendmail->mail($user[reg_email],$mail[subject],$mail[body],$mail[header]);
				### OUTPUT ###
				eval("\$inc[action] = \"".gettemplate("user.forgotpassword.senddone")."\";");
			}
		}
	}
	##########################################
	if($action=="savenewpassword") {
		if($pass1 != $pass2) {
			eval("\$fail 		= \"".gettemplate("fail.notthesame")."\";");
			eval("\$inc[action] 	= \"".gettemplate("user.forgotpassword.changepassword")."\";");
		} else {
			### CHANGE PASSWORD ###
			$db->query_str("UPDATE $tab[user] SET user_password='".md5($pass1)."', md5='1', lostpassword='0' WHERE lostpassword='$key'");
			### DELETE KEY ###								
			eval("\$inc[action] 	= \"".gettemplate("user.forgotpassword.changedone")."\";");
		}
	}
	##########################################
	if($action=="forgotnpasswordstep2") {
		if($key) {
			$user 	= $db->query("SELECT * FROM $tab[user] WHERE lostpassword='$key'");
			if($user) {
				$user[user_name] 	= mkuser("user_name",0,$user);
				eval("\$inc[action] 	= \"".gettemplate("user.forgotpassword.changepassword")."\";");
			} else {eval("\$inc[action] 	= \"".gettemplate("user.forgotpassword.wrongkey")."\";");}
		}
	}
	##########################################
	if($action=="voteuser_pos") {
		$stopheaderoutput_main =  TRUE;
		if($userid) {
			if(!gettmp("uservote",$cid,$userid)) {
				$db->query_str("UPDATE $tab[user] SET 	rate_points = rate_points+1,
									rate_count  = rate_count+1 WHERE id='$userid'");
				savetmp("uservote",$cid,$userid);
				eval("\$inc[action] = \"".gettemplate("user.uservote.success")."\";");
			} else {
				eval("\$inc[action] = \"".gettemplate("user.uservote.reload")."\";");
			}
		}
	}
	##########################################
	if($action=="voteuser_neg") {
		$stopheaderoutput_main =  TRUE;
		if($userid) {
			if(!gettmp("uservote",$cid,$userid)) {
				$db->query_str("UPDATE $tab[user] SET 	rate_points=rate_points+1, 
									rate_count=rate_count-1 WHERE id='$userid'");
				savetmp("uservote",$cid,$userid);
				eval("\$inc[action] = \"".gettemplate("user.uservote.success")."\";");
			} else {
				eval("\$inc[action] = \"".gettemplate("user.uservote.reload")."\";");
			}
		}
	}
	##########################################
	if($action=="forgotnpasswordstep1" || $fail_forgotnpwd) {
		$fail 			= $fail_forgotnpwd;
		eval("\$inc[action] 	= \"".gettemplate("user.forgotpassword.step1")."\";");
	}
	##########################################
	if($action=="ugbdelete") {
		if($id) {
			if(!($e=getugb($id)) || $e[uid] != $login[id]) {
				eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
			} else {
				$db->query_str("DELETE FROM $tab[ugb] WHERE id='$e[id]'");
				$action = "ugb";
			}
		} else header("LOCATION: $HTTP_REFERER");
	}
	##########################################
	if($action=="ugb_save") {
		if(!$form[text] || !$form[title]) {
			eval("\$fail_ugb = \"".gettemplate("fail.eingabe")."\";");
		} else {
			if($db->query("SELECT id FROM $tab[ugb] WHERE text='$form[text]'")) {
				eval("\$fail_ugb = \"".gettemplate("fail.twice")."\";");
			} else {
				$db->query_str("INSERT INTO $tab[ugb] (uid,aid,time,title,text) VALUES ('$uid','$login[id]','".time()."','$form[title]','$form[text]')");
				if($db->query("SELECT * FROM $tab[useroption] WHERE `name`='alertugb' AND `value`='1' AND `userid`='$uid'")) {
					$user = getuser($uid);

					eval("\$subject = \"".gettemplate("user.ugb.mail.subject")."\";");
					eval("\$body = \"".gettemplate("user.ugb.mail.body")."\";");
					eval("\$header = \"".gettemplate("user.ugb.mail.header")."\";");

					$sendmail->mail($user[user_email],$subject,$body,$header);
				}
				unset($form);
			}
		}
		$action = "ugb";
	}
	##########################################
	if($action=="ugb" || $fail_ugb) {
		$stopheaderoutput_main = true;
		$fail = $fail_ugb;
		if(!is_numeric($uid) || !$user = getuser($uid)) {
			eval("\$inc[action] = \"".gettemplate("user.ugb.invaliduser")."\";");
		} else {
			$username = mkuser("user_name","",$user);
			$result = $db->query_str("SELECT * FROM $tab[ugb] WHERE uid='$uid' ORDER BY id DESC");
			while($e = $db->fetch_array($result)) {
				$wuser = getuser($e[aid]);
				$autor = mkuser("user_name","",$wuser);
				$e[text] = mksworacodes($e[text]);
				$time = mkdate($e[time]);
				eval("\$bit .= \"".gettemplate("user.ugb.bit")."\";");
			}
			if(!$bit) {
				eval("\$bit = \"".gettemplate("user.ugb.nobit")."\";");
			}
			$loginname = mkuser("user_name","",$login);
			eval("\$inc[action] = \"".gettemplate("user.ugb.main")."\";");
		}
	}
	##########################################
##########################################
} else {
	###########
	if(!$show && !$login[id]) {$show = "newuserterms";}
	###########
	if($show) {
		#####
		if($show=="newuserterms") {
			eval("\$inc[action] = \"".gettemplate("user.terms")."\";");
		}
		#####
		if($show=="newuser") {
			eval("\$inc[action] 	= \"".gettemplate("user.newuser.form")."\";");
		}
		#####
		if($show=="avatarpic") {
			if(trim($str=getuseravatar($userid))) {
				header("Content-Type: image/gif");
				echo $str;
			} else {
				echo "<!-- invalid avatar //-->";
			}
			exit;
		}
		#####
		if($show=="user") {
			$stopheaderoutput_main 	= TRUE;
			if(!$login[id]) {
				eval("\$inc[action] = \"".gettemplate("fail.access.notloggedin")."\";");
			} else {
				$user = getuser($userid);
				if(!$user) {
					eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
				} else {
					/////////////////////USER INFOS//////////////////////
					$reg_date 		= mkdate($user[reg_date],"d.m.Y");
					$user_birth 		= mkdate($user[user_birth],"d.m.Y");
					$user_login 		= mkuser("user_login",0,$user);
					$user_name 		= mkuser("user_name",0,$user);
					$user_gender 		= mkuser("user_gender",0,$user);
					$user_icq 		= mkuser("user_icq",0,$user);
					$user_aim 		= mkuser("user_aim",0,$user);
					$user_yim 		= mkuser("user_yim",0,$user);
					$user_hp 		= mkuser("user_hp",0,$user);
					$user_interests 	= mkuser("user_interests",0,$user);
					$user_work 		= mkuser("user_work",0,$user);
					$user_location 		= mkuser("user_location",0,$user);
					$avatar 		= mkuser("avatar",0,$user);
					/////////////////////////////////////////////////////
					$user_text 		= mkuser("user_text",0,$user);
					eval("\$user_stats 	= \"".gettemplate("user.show.user.userstats")."\";");
					eval("\$user_infos 	= \"".gettemplate("user.show.user.userinfos")."\";");
					eval("\$user_activ 	= \"".gettemplate("user.show.user.useractivity")."\";");
					eval("\$inc[action] 	= \"".gettemplate("user.show.user.main")."\";");
				}
			}
		}
	}
	##########
	if($show=="listusers" || !$inc[action]) {
		##########
		if(!$show)	 	{$show  = 15;}
		if(!$sort) 		{$sort 	= "DESC";}
		if(!$order) 		{$order	= "id";}
		if(!$start)		{$start = 0;}
		$order_like_this 	= $order;
		$sort_like_this 	= $sort;
			###
		$userq = $db->query_str("SELECT * FROM $tab[user] WHERE activated='1' AND blocked='0' AND id<>1 ORDER BY $order_like_this $sort_like_this LIMIT $start,$show");
		while($userf = $db->fetch_array($userq)) {
			$user 		= getuser($userf[id]);
			$user_name 	= mkuser("user_name",0,$user);
			#$user_pm 	= mkuser("user_pm",0,$user);
			$user_email 	= mkuser("user_email",0,$user);
			$user_hp 	= mkuser("user_hp",0,$user);
			$user_icq 	= mkuser("user_icq",0,$user);
			$user_aim 	= mkuser("user_aim",0,$user);
			$user_yim 	= mkuser("user_yim",0,$user);

			eval("\$userlist .= \"".gettemplate("user.listusers.userbit")."\";");
		}
		list($count) 	= $db->query("SELECT COUNT(*) FROM $tab[user] WHERE activated='1' AND blocked='0' AND id<>1");
		$param["s$show"] 	= " selected";
		$param[$order] 	= " selected";
		$param[$sort] 	= " selected";
		$links 		= mkpages($count,$show,"user.listusers.pagebit");

		eval("\$pages = \"".gettemplate("user.listusers.pages")."\";");
		if($sort=="DESC") { unset($sort); $sort[$order] = "ASC"; }
		if($sort=="ASC")  { unset($sort); $sort[$order] = "DESC";}
		eval("\$inc[action] = \"".gettemplate("user.listusers.main")."\";");
	}
		#####
}

?>