<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

////////////////////////////////////////////
if($action) {
	##########################
	if($action=="save_reply") {
		if(!$login[id]) 	$login[id] = "1";
		if(!$form[text]) 	{eval("\$fail_reply = \"".gettemplate("fail.eingabe")."\";");}
		else {
			if($db->query("SELECT * FROM $tab[forum_post] WHERE post_title='$form[title]' AND post_text='$form[text]' AND aut_id='$login[id]' AND parent_postid='$threadid'")) {
				eval("\$incf[action] = \"".gettemplate("forum.fail.post.twice")."\";");
			} else {
				$insertstring = $db->query_str("INSERT INTO $tab[forum_post] (parent_boardid,parent_postid,settime,is_first,aut_id,post_title,post_text,smilies,signatur) 
										VALUES	('$boardid','$threadid','".time()."','0','$login[id]','$form[title]','$form[text]','$form[smilies]','$form[signatur]')");
				$postid = mysql_insert_id();
				$updatethread =	$db->query_str("UPDATE $tab[forum_post] SET 
										count_replys=count_replys+1,
										last_userid='$login[id]',
										last_postid='$postid',
										last_posttime='".time()."'
										WHERE id='$threadid'");
				$updateboard =	$db->query_str("UPDATE $tab[forum_board] SET 
										count_posts=count_posts+1,
										last_userid='$login[id]',
										last_postid='$postid'
										WHERE id='$boardid'");
				addpoints($points[forum_newpost]);
				set_forum_notify();
				header("LOCATION: index.php?section=forum&swora=".session_id()."&boardid=$boardid&threadid=$threadid&start=$start");
			}
		}
	}
	##########################
	if($action=="delpost") {
		$post = getpost($postid);
		if(($post[aut_id] != $login[id]) && !is_allowed($sec[forum][id],$boardid)) {
			eval("\$incf[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			if($post[is_first]) {
				$parentposts 	= $db->query("SELECT COUNT(*) FROM $tab[forum_post] WHERE parent_postid='$postid'");
				$query_str 	= $db->query_str("DELETE FROM $tab[forum_post] WHERE parent_postid='$postid'");
				$query_str 	= $db->query_str("DELETE FROM $tab[forum_post] WHERE id='$postid'");
				$count_posts 	= $thisboard[count_posts]-=$parentposts[0];
				$UPDATE 	= $db->query_str("UPDATE $tab[forum_board] SET count_threads=count_threads-1, count_posts='$count_posts' WHERE id='$boardid'");
			} else {
				$query_str 	= $db->query_str("DELETE FROM $tab[forum_post] WHERE id='$postid'");
				$UPDATE 	= $db->query_str("UPDATE $tab[forum_board] SET count_posts=count_posts-1 WHERE id='$boardid'");
			}
			if($post[is_first])	header("LOCATION: index.php?section=forum&swora=".session_id()."&boardid=$boardid&start=$start");
			else			header("LOCATION: index.php?section=forum&swora=".session_id()."&boardid=$boardid&threadid=$threadid&start=$start");
		}
	}
	##########################
	if($action=="save_thread") {
		if(!$login[id]) 	$login[id] = "1";
		if(!$form[title] || !$form[text]) {eval("\$fail_thread = \"".gettemplate("fail.eingabe")."\";");}
		else {
			if($db->query("SELECT * FROM $tab[forum_post] WHERE post_title='$form[title]' AND post_text='$form[text]' AND aut_id='$login[id]' AND parent_boardid='$boardid'")) {
				eval("\$incf[action] = \"".gettemplate("forum.fail.post.twice")."\";");
			} else {
				$boardposts = 	$thisboard[count_posts] + 1;
				$boardthreads = $thisboard[count_threads] + 1;
				$insertstring = $db->query_str("INSERT INTO $tab[forum_post] (parent_boardid,settime,is_first,aut_id,post_title,post_text,smilies,signatur) 
										VALUES ('$boardid','".time()."','1','$login[id]','$form[title]','$form[text]','$form[smilies]','$form[signatur]')");
				$postid = mysql_insert_id();
				$id = 		$db->query_str("UPDATE $tab[forum_post] SET 
									last_userid='$login[id]',
									parent_postid='$postid',
									last_posttime='".time()."'
									WHERE id='$postid'");
				$updateboard =	$db->query_str("UPDATE $tab[forum_board] SET 
										count_threads=count_threads+1,
										count_posts=count_posts+1,
										last_userid='$login[id]',
										last_postid='$postid'
										WHERE id='$boardid'");
				addpoints($points[forum_newthread]);
				set_forum_notify();
				header("LOCATION: index.php?section=forum&swora=".session_id()."&boardid=$boardid&threadid=$postid&start=$start");
			}
		}
	}
	##########################
	if($action=="save_edit_thread") {
		$post = getpost($postid);
		if($post[is_first] && !$form[title]) {eval("\$fail_edit = \"".gettemplate("fail.eingabe")."\";");}
		elseif(($post[aut_id]!=$login[id]) && !is_allowed($sec[forum][id],$boardid)) {
			eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			if(!$form[text]) 	{eval("\$fail_edit = \"".gettemplate("fail.eingabe")."\";");
			} else {
				$id = $db->query_str("UPDATE $tab[forum_post] SET 
							post_title='$form[title]',
							post_text='$form[text]',
							smilies='$form[smilies]',
							signatur='$form[signatur]'
							WHERE id='$postid'");
				set_forum_notify(0,$post[aut_id]);
				header("LOCATION: index.php?section=forum&swora=".session_id()."&boardid=$boardid&threadid=$threadid&start=$start");
			}
		}
	}
	##########################










	##########################
	if($action=="reply" || $fail_reply) {
	 	if(!$login[id] && !$config[forum_guestpost]) {
		 	eval("\$incf[action] = \"".gettemplate("fail.access.notloggedin")."\";");
		 } else {
		 
			if(!$login[id]) { $login[user_name] = "Gast";$notifybox = " disabled";}
			$fail 		= $fail_reply;
			$actionsave	= "save_reply";
			$schecked	= " checked";
			$th_title	= "Antworten";
			$user_name	= mkuser("user_name",0,$login);
			$smilies 	= getsmiliesbit("forum.smilies.bit");
			eval("\$incf[action] = \"".gettemplate("forum.new.post")."\";");
		}
	}
	##########################
	if($action=="newthread" || $fail_thread) {
	 	if(!$login[id] && !$config[forum_guestpost]) {
	 		eval("\$incf[action] = \"".gettemplate("fail.access.notloggedin")."\";");
	 	} else {
		 
			if(!$login[id]) {$login[user_name] = "Gast";$notifybox=" disabled";}
			$fail		= $fail_thread;
			$actionsave	= "save_thread";
			$schecked	= " checked";
			$th_title	= "Neues Thema";
			$user_name	= mkuser("user_name",0,$login);
			$smilies 	= getsmiliesbit("forum.smilies.bit");
			eval("\$incf[action] = \"".gettemplate("forum.new.post")."\";");
		}
	}
	##########################
	if($action=="editpost" || $fail_edit) {
		$post = getpost($postid);
		if(($post[aut_id] != $login[id]) && !is_allowed($sec[forum][id],$boardid)) {
			eval("\$incf[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			$notifybox 	= list($a)=$db->query("SELECT id FROM $tab[forum_notify] WHERE userid='$post[aut_id]' AND threadid='$threadid'") ? " checked" : " unchecked";
			if($post[smilies]) $schecked = " checked";
			$fail		= $fail_edit;
			$actionsave	= "save_edit_thread";
			$th_title	= "Post bearbeiten";
			$user_name	= mkuser("user_name",$post[aut_id],$NULL);
			$form[title]	= $post[post_title];$form[text]=$post[post_text];
			$smilies 	= getsmiliesbit("forum.smilies.bit");
			eval("\$incf[action] = \"".gettemplate("forum.new.post")."\";");
		}
	}
	###########################
} else {
##########################
	if(!$start)  $start = "0";
	if(!$showpp) $showpp = $config[forum_show_postspp];

	$countposts = $db->query("SELECT COUNT(*) FROM $tab[forum_post] WHERE parent_boardid='$boardid' AND parent_postid='$threadid'"); $countposts = $countposts[0];
	if($countposts > $config[forum_show_postspp]) {
		while($countposts > 0) {
			$countposts -= $config[forum_show_postspp];
			if($started) {
				$number++;
				$startnow += $config[forum_show_postspp];
			} else { 
				$started++;
				$number = "1";
				$startnow = "0";
			}
			eval("\$inc[showpp] .= \"".gettemplate("forum.showpp.posts.num")."\";");
		}
	}

	$UPDATE 	= $db->query_str("UPDATE $tab[forum_post] SET count_views=count_views+1 WHERE id='$threadid'");
	$postsquery 	= $db->query_str("SELECT * FROM $tab[forum_post] WHERE parent_postid='$threadid' ORDER BY is_first DESC, settime ASC LIMIT $start,$showpp");
	$views		= $thisthread[count_views]+1;
	if(!mysql_num_rows($postsquery)) {eval("\$incf[posts] = \"".gettemplate("forum.wrongid")."\";");}
	else {
		while($post = $db->fetch_array($postsquery)) {
			/////////////////////////////////////////////////////
			if(!$post[post_title]) $post[post_title] = "<br />";
			$post[post_text] = str_replace("<","&lt;",$post[post_text]);
		    $post[post_text] = str_replace(">","&gt;",$post[post_text]);
			$post[post_text] = mksworacodes($post[post_text],$post[aut_id]);
			$post[post_text] = str_replace("\r\n","<br>",$post[post_text]);

			// ------- //
			$user 		= getuser($post[aut_id]);
			$user_name 	= mkuser("user_name",0,$user);
			$user_location	= mkuser("user_location",0,$user);
			$avatar 	= mkuser("avatar",0,$user);
			$user_points 	= ($user[points]) ? mkuser("points",0,$user) : NULL ;

			if($post[signatur]) $post[post_text].="<br><br>".mkuser("user_signatur",0,$user);
			if($post[smilies])  $post[post_text] = makesmilies($post[post_text]);
			// ------- //

			eval("\$userstuff = \"".gettemplate("forum.thread.post.userstuff")."\";");
			eval("\$poststuff = \"".gettemplate("forum.thread.post.poststuff")."\";");

			if($user[id]!="1")	eval("\$userstuffsec = \"".gettemplate("forum.thread.post.userstuffsec")."\";");
			else			unset($userstuffsec);

			eval("\$incf[postbit] .= \"".gettemplate("forum.thread.post.bit")."\";");
		}
		eval("\$incf[posts] = \"".gettemplate("forum.thread.post")."\";");
	}
	eval("\$inc[verzeichnis] = \"".gettemplate("forum.thread.verzeichnis")."\";");
	eval("\$incf[action] = \"".gettemplate("forum.thread")."\";");
}
?>
