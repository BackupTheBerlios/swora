<?

if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

if(!$login[id]) {
	eval("\$inc[action] = \"".gettemplate("fail.access.notloggedin")."\";");
} else {
	################
	if($action) {
		if($action=="saveuserdetails") {
			if(!$form[user_name]) {
				eval("\$fail = \"".gettemplate("fail.eingabe")."\";");
			} else {
				$birthstamp = mktime(0,0,0,$form[birthmonth],$form[birthday],$form[birthyear]);
				$db->query_str("UPDATE $tab[user] SET
							user_name='$form[user_name]',
							user_email='$form[user_email]',
							user_icq='$form[user_icq]',
							user_aim='$form[user_aim]',
							user_yim='$form[user_yim]',
							user_hp='$form[user_hp]',
							user_interests='$form[user_interests]',
							user_text='$form[user_text]',
							user_location='$form[user_location]',
							user_birth='$birthstamp',
							user_work='$form[user_work]',
							user_gender='$form[user_gender]'
							WHERE id='$login[id]'");
				$login = checkuser();
			}
		}
		###############
		if($action=="savepassword") {
			if(!$pass0 || !$pass1 || !$pass2) {eval("\$fail = \"".gettemplate("fail.eingabe")."\";");}
			elseif($pass1 != $pass2) {eval("\$fail = \"".gettemplate("fail.notthesame")."\";");}
			else {
				### Checkpassword ###
				$check = $db->query("SELECT * FROM $tab[user] WHERE user_password='".md5($pass0)."' AND id='$login[id]'");
				if(!$check) {
					eval("\$fail = \"".gettemplate("fail.password.short")."\";");
				} else {
					$db->query_str("UPDATE $tab[user] SET user_password='".md5($pass2)."', md5='1' WHERE id='$login[id]'");
					//$login 	= checkuser();
					$loginsession 	= $db->query("SELECT * FROM $tab[user] WHERE id='$login[id]'");

					eval("\$fail = \"".gettemplate("profil.success.pass")."\";");
				}
			}
		}
		###############
		if($action=="save_signatur") {
			$sql 	= $db->query_str("UPDATE $tab[user] SET user_signatur='$form[user_signatur]' WHERE id='$login[id]'");
			$login 	= checkuser();
			eval("\$fail = \"".gettemplate("success")."\";");
		}
		###############
		if($action=="save_privacy") {
			if($result = $db->query("SELECT * FROM $tab[useroption] WHERE userid='$login[id]' AND `name`='show_email'"))
				$db->query_str("UPDATE $tab[useroption] SET value='$form[show_email]' WHERE id='$result[id]'");
			else 	$db->query_str("INSERT INTO $tab[useroption] (userid,name,value) VALUES ('$login[id]','show_email','$form[show_email]')");

			if($result = $db->query("SELECT * FROM $tab[useroption] WHERE userid='$login[id]' AND `name`='newsletter'"))
				$db->query_str("UPDATE $tab[useroption] SET value='$form[newsletter]' WHERE id='$result[id]'");
			else 	$db->query_str("INSERT INTO $tab[useroption] (userid,name,value) VALUES ('$login[id]','newsletter','$form[newsletter]')");

			$dir = @dir(@dirname(__FILE__));
			while($f = $dir->read()) {
				if(preg_match("#^addon#i",$f)) include(@dirname(__FILE__)."/".$f);
			}


			$login 		= checkuser();
		}
		###############
		if($action=="deleteaccount") {
			if($form[yes]) {
				$db->query_str("UPDATE $tab[user] SET activated='0' WHERE id='$login[id]'");
				eval("\$mail_body 	= \"".gettemplate("profil.mail.deleteaccount.body")."\";");
				eval("\$mail_subject 	= \"".gettemplate("profil.mail.deleteaccount.subject")."\";");
				eval("\$mail_header 	= \"".gettemplate("profil.mail.deleteaccount.header")."\";");
				$sendmail->mail($login[reg_email],$mail_subject,$mail_body,$mail_header);

				checkuser(1);
				header("LOCATION: index.php");
			}
			if($form[no]) {unset($inc[action]);}
		}
		###############
		if($action=="save_avatar") {
			$avatarpic 		= $HTTP_POST_FILES[avatarpic];
			$avatarpic_name 	= $avatarpic[name];
			$avatarpic_path 	= $avatarpic[tmp_name];
			$avatarpic_type 	= $avatarpic[type];
			$avatarpic_size 	= $avatarpic[size];

			if($form[unlink]) {
				$olduseravatar 	= $db->query_str("SELECT * FROM $tab[avatar] WHERE userid=1 LIMIT 1");
				if($oldavatar[path])unlink($oldavatar[path]);
				$olddbentrys 	= $db->query_str("DELETE FROM $tab[avatar] WHERE userid='$login[id]'");
			} elseif($form[link]) {
				if($fp=@fopen($form[link],"rb")) {
					$olduseravatar 		= $db->query_str("SELECT * FROM $tab[avatar] WHERE userid=1 LIMIT 1");
					if($oldavatar[path])	unlink($oldavatar[path]);
					$olddbentrys 		= $db->query_str("DELETE FROM $tab[avatar] WHERE userid='$login[id]'");
					$db->query_str("INSERT INTO $tab[avatar] (userid,path) VALUES ('$login[id]','$form[link]')");
					fclose($fp);
				} else {
					eval("\$fail_avatar = \"Datei ungültig\";");
				}
			} elseif(@is_file($avatarpic_path)) {
				if($config[avatar_ftpid]) {
					if( $avatarpic_size < $config[avatar_maxsize] || is_allowed("any")) {
						if(preg_match("#^image#",$avatarpic_type)) {
							$name = generatepass(15);
							$accesscodes = getftpaccesscodes($config[avatar_ftpid]);
							if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd])) {
								if($fp=fopen($avatarpic_path,"rb")) {								
									$ftp->setmode(2);
									if($ftp->cfput($fp,$accesscodes[path].$name)) {
										$olduseravatar = $db->query("SELECT * FROM $tab[avatar] WHERE userid=1 LIMIT 1");
										if($oldavatar[path])unlink($oldavatar[path]);
										$olddbentrys = $db->query_str("DELETE FROM $tab[avatar] WHERE userid='$login[id]'");
										$db->query_str("INSERT INTO $tab[avatar] (userid,path) VALUES ('$login[id]','$name')");
									} else {
										eval("\$fail_avatar = \"Upload Failed\";");
									}
									$ftp->disconnect();
									@fclose($fp);
								} else {
									eval("\$fail_avatar = \"FileOpen Failed\";");
								}
							} else {
								eval("\$fail_avatar = \"FTP Connection Failes\";");
							}
						} else {
							eval("\$fail_avatar = \"Nur Bilder bitte\";");
						}
					} else {
						eval("\$fail_avatar = \"Image zu groß\";");
					}
					//////////////////////////
				} else {
					eval("\$fail_avatar = \"Der AvatarUpload wurde noch nicht konfiguriert.\";");
				}
			} else {
				eval("\$fail_avatar = \"Datei ungültig\";");
			}
			//////////////////////////
			unset($form);
			$show="avatar";
		}
	###############
	###############
	}
	################
	###############
	if($show) {
		if($show=="userdetails") {
			if($login[gender] == "2") 	$female	= " selected";
			else				$male	= " selected";
			$birthday 	= date("d",$login[user_birth]);
			$birthmonth 	= date("m",$login[user_birth]);
			$birthyear 	= date("Y",$login[user_birth]);
			eval("\$inc[action] = \"".gettemplate("profil.userdetails")."\";");
		}
		#############
		if($show=="password") {
			$user_name 		= mkuser("user_name",$login[id],$NULL);
			eval("\$inc[action] 	= \"".gettemplate("profil.changepassword")."\";");
		}
		#############
		if($show=="signatur") {
			$smilies 		= getsmiliesbit("profil.smilie.bit");
			eval("\$inc[action] 	= \"".gettemplate("profil.signatur")."\";");
		}
		#############
		if($show=="privacy") {
			$query = $db->query_str("SELECT * FROM $tab[useroption] WHERE userid='$login[id]'");
			while($result = $db->fetch_array($query)) {
				if($result[value]) ${"$result[name]"} = " checked";
				if(!$result[value]) ${"$result[name]"} = " unchecked";
			}

			$dir = dir($style[templatefolder]."/profil/");
			while($f = $dir->read()) {
				if(preg_match("#^(profil.privacy.addon.(.*))(\.html)#i",$f,$re))
					eval("\$addons .= \"".gettemplate("$re[1]")."\";");
			}

			eval("\$inc[action] 	= \"".gettemplate("profil.privacy")."\";");
		}
		#############
		if($show=="avatar") {
			if($config[avatar_ftpid]) {
				$fail = $fail_avatar;
				if(getuseravatar()) 	{eval("\$avatarpic = \"".gettemplate("profil.avatarpic")."\";");}
				else 			{unset($avatarpic);}
				$size 	= round($config[avatar_maxsize]/(1024),2);
				eval("\$inc[action] = \"".gettemplate("profil.avatar")."\";");
			} else {
				eval("\$inc[action] = \"".gettemplate("profil.avatar.notavailible")."\";");
			}
		}
		#############
		if($show=="avatarpic") {
			if($str=getuseravatar()) {
				header("Content-Type: image/gif");
				echo $str;
			} else {
				echo @implode("",@file("images/spacer.gif"));
			}
			exit;
		}
		#############
		if($show=="deleteaccount") {
			eval("\$inc[action] = \"".gettemplate("profil.delaccount")."\";");
		}
		#############
		if($show=="capacity") {
			$countout 	= $db->query("SELECT COUNT(*) FROM $tab[pm] WHERE autid='$login[id]' AND outbox='1' AND inbox='0'");
			$countin 	= $db->query("SELECT COUNT(*) FROM $tab[pm] WHERE toid='$login[id]' AND outbox='0' AND inbox='1'");
			$countfol 	= $db->query("SELECT COUNT(*) FROM $tab[folder] WHERE userid='$login[id]'");

			$statusbar[folder] 	= mkstatus($countfol[0],$config[max_folders],1);
			$statusbar[outgoing] 	= mkstatus($countout[0],$config[max_pms_outbox],1);
			$statusbar[incoming] 	= mkstatus($countin[0],$config[max_pms_inbox],1);

			$status[folder] 	= round(100*($countfol[0]/$config[max_folders]),1);
			$status[outgoing] 	= round(100*($countout[0]/$config[max_pms_outbox]),1);
			$status[incoming] 	= round(100*($countin[0]/$config[max_pms_inbox]),1);

			eval("\$inc[action] 	= \"".gettemplate("profil.capacity")."\";");
		}
		############
	}
	################
	else {eval("\$inc[action] 	= \"".gettemplate("profil.main")."\";");}
}


?>
