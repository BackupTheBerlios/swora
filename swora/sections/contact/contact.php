<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");
	if($action) {
		###########################
		if($action=="check") {
			if(!$form[name] || !$form[email] || !$form[text]) {eval("\$fail = \"".gettemplate("fail.eingabe")."\";");}
			elseif(!checkmail($form[email])) {eval("\$fail = \"".gettemplate("fail.eingabe.invalid.mail")."\";");}
			else {
				unset($tomailaddr);
				### Get target ###
				if($form[section]) {
					$rightquery = $db->query_str("SELECT * FROM $tab[right] WHERE section='$form[section]'");
					while($right = $db->fetch_array($rightquery)) {
						$mod = 			getuser($right[userid]);
						if($mod[user_email])	$tomailaddr[] = $mod[user_email];
						else			$tomailaddr[] = $mod[reg_email];
					}
				}
				########
				if(!$tomailaddr){$tomailaddr[] = $config[master_email];}
				########
				$date = mkdate();
				eval("\$mail[subject] = \"".gettemplate("contact.mail.subject")."\";");
				eval("\$mail[body] = \"".gettemplate("contact.mail.body")."\";");
				eval("\$mail[header] = \"".gettemplate("contact.mail.header")."\";");
				######## SEND ###########
				foreach($tomailaddr as $mailaddr) {$sendmail->mail($mailaddr, $mail[subject],$mail[body],$mail[header]);}
				#########################
				eval("\$inc[action] = \"".gettemplate("contact.done")."\";");
			}
		}
		#########################
		if($action=="senduser") {
			$user = getuser($userid);
			if(!$form[name] || !$form[email] || !$form[text]) {eval("\$fail = \"".gettemplate("fail.eingabe")."\";");}
			elseif(!checkmail($form[email])) {eval("\$fail = \"".gettemplate("fail.eingabe.invalid.mail")."\";");}
			else {
				$date = mkdate();
				eval("\$mail[subject] = \"".gettemplate("contact.user.mail.subject")."\";");
				eval("\$mail[body] = \"".gettemplate("contact.user.mail.body")."\";");
				eval("\$mail[header] = \"".gettemplate("contact.user.mail.header")."\";");
				$sendmail->mail($user[user_email], $mail[subject],$mail[body],$mail[header]);
			}
			eval("\$inc[action] = \"".gettemplate("contact.done")."\";");
		}
		#########################
		if($action=="sendtomail") {
			$email = base64_decode($mi);
			if(!$form[name] || !$form[email] || !$form[text]) {eval("\$fail = \"".gettemplate("fail.eingabe")."\";");}
			elseif(!checkmail($form[email])) {eval("\$fail = \"".gettemplate("fail.eingabe.invalid.mail")."\";");}
			else {
				$date = mkdate();
				eval("\$mail[subject] = \"".gettemplate("contact.user.mail.subject")."\";");
				eval("\$mail[body] = \"".gettemplate("contact.user.mail.body")."\";");
				eval("\$mail[header] = \"".gettemplate("contact.user.mail.header")."\";");
				$sendmail->mail($email, $mail[subject],$mail[body],$mail[header]);
			}
			eval("\$inc[action] = \"".gettemplate("contact.done")."\";");
		}
		#########################
	}
	################################################
	if(!$action || $fail){
		if($who) {
			switch($who) {
				case 'moderator':
					$mods = $db->query_str("SELECT * FROM $tab[section] WHERE active='1' ORDER BY id");
					while($se = $db->fetch_array($mods)) {
						if(($se[id] == $sec[contact][id]) || !$se[name]) continue;
						eval("\$selectmods .= \"".gettemplate("contact.sektion.select")."\";");
					}
					eval("\$inc[who] = \"".gettemplate("contact.who.moderator")."\";");
					eval("\$inc[action] = \"".gettemplate("contact.form")."\";");
				break;

				case 'user':
					$user = getuser($userid);
					if(!$user) {eval("\$inc[action] = \"".gettemplate("fail.invalid.username")."\";");}
					else {
						if($user[show_email]) $user_email = mkuser("user_email",0,$user);
						$user_name = mkuser("user_name",0,$user);
						eval("\$inc[action] = \"".gettemplate("contact.who.user")."\";");
					}
				break;

				case 'mailing':
					$user_name = $uname;
					eval("\$inc[action] = \"".gettemplate("contact.who.mailing")."\";");				
			}
		}
		if(!$inc[action]) {
			eval("\$inc[who] = \"".gettemplate("contact.who.webmaster")."\";");
			eval("\$inc[action] = \"".gettemplate("contact.form")."\";");
		}
	}
	################################################


?>