<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

if($action) {
	################
	if($action=="new_new_save") {
		$user 		= getuser($login[id]);
		$time 		= time();
		$testquery 	= $db->query("SELECT * FROM $tab[news] WHERE title='$form[title]' AND text='$form[text]'");
		if($testquery) {
			eval("\$inc[action] = \"".gettemplate("news.fail.newstwice")."\";");
		} else {
			if($form[admin_puplic] && is_allowed($sec[news][id]))  $puplic="1";
			else $puplic="0";
			$savenewsquery = $db->query_str("INSERT INTO $tab[news] (autid,date,title,text,smilies,html,activated) 
							VALUES ('$login[id]','$time','$form[title]','$form[text]','$form[smilies]','$form[html]','$puplic')");		
			eval("\$inc[action] = \"".gettemplate("news.success.send")."\";");
		}
	}
	################
	if($action=="new_comment_save") {
		if(!$newsid) header("LOCATION: index.php?section=news");
		if($form[autname] && $form[title] && $form[text] && $newsid) {
			$time 	= time();
			$query 	= $db->query_str("INSERT INTO $tab[news_comment] (newsid,autname,title,text,date) 
						VALUES('$newsid','$form[autname]','$form[title]','$form[text]','$time')");
			addpoints($points[news_com]);
			unset($form);
		} else {eval("\$fail_comment = \"".gettemplate("fail.eingabe")."\";");}
		$action="news_comment";
	}




	################
	if($action=="news_new") {
		if(!$login) {
			eval("\$inc[action] = \"".gettemplate("fail.access.notloggedin")."\";");
		} else {
			$user 		= getuser($login[id]);
			$smilies 	= getsmiliesbit("news.newsform.smilie.bit");
			$user_name 	= mkuser("user_name",0,$login);
			$thisaction 	= "new_new_save";

			if(is_allowed($sec[news][id])) {eval("\$admin = \"".gettemplate("news.newsform.admin")."\";");}
			eval("\$inc[action] = 	\"".gettemplate("news.newsform")."\";");
		}
	}
	################
	if($action=="news_comment" || $fail_comment) {
		if(!$newsid) 	header("LOCATION: index.php?section=news");
		$fail 		= $fail_comment;
		$comquery 	= $db->query_str("SELECT * FROM $tab[news_comment] WHERE newsid='$newsid'");
		if(!mysql_num_rows($comquery)) {eval("\$inc[combit] = \"".gettemplate("news.comment.nocoms")."\";");}
		else {
			while($com = $db->fetch_array($comquery)) {
				$com[date] 	= mkdate($com[date]);
				eval("\$inc[combit] .= \"".gettemplate("news.comment.bit")."\";");
			}
		}
		eval("\$inc[form] = \"".gettemplate("news.commentform")."\";");
		eval("\$inc[action] = \"".gettemplate("news.comment")."\";");
	}
	################
}
//////////////////////////////////////////////////////
if(!$inc[action]){
	/* --- NEWS --- */
	if(!$newsid) {$newsquery = $db->query_str("SELECT * FROM $tab[news] WHERE activated='1' AND blocked='0' ORDER BY date DESC LIMIT $config[news_show]");
		      $newsid = $db->query("SELECT id FROM $tab[news] ORDER BY date DESC LIMIT 1");$newsid=$newsid[0];
	} else 	     {$newsquery = $db->query_str("SELECT * FROM $tab[news] WHERE id='$newsid'");}
	while($news = $db->fetch_array($newsquery)) {

		$comments = 		$db->query("SELECT COUNT(*) FROM $tab[news_comment] WHERE newsid='$news[id]'"); $comments=$comments[0];
		$user_name = 		mkuser("user_name",$news[autid],$NULL);
		$news[date] = 		mkdate($news[date]);
		if(!$news[html]) 	{$news[text] = htmlspecialchars($news[text]);}
		if($news[smilies]) 	{$news[text] = makesmilies($news[text]);}
		$news[text] = 		str_replace("\r\n","<br>",$news[text]);
		$news[text] = 		mksworacodes($news[text],$news[autid]);

		eval("\$inc[news]  .= \"".gettemplate("news.news")."\";");
	}
	/* --- LISTE --- */
	if($config[news_list]) {
		$newsquery = $db->query_str("SELECT id,title FROM $tab[news] WHERE activated='1' AND blocked='0' ORDER BY date DESC LIMIT $config[news_list]");
		while($news = $db->fetch_array($newsquery)) {
			$user_name = 		mkuser("user_name",$news[autid],$NULL);
			$news[date] = 		mkdate($news[date]);
			eval("\$latestnews.=\"".gettemplate("news.newtitles.bit")."\";");
		}
		eval("\$oldernews = \"".gettemplate("news.oldernews")."\";");
	}
	/* --- INCLUDE --- */
	if($config[news_include_others]) {
		ob_start();
			include($config[news_include_others]);
			$newsincludeoutput = ob_get_contents();
		ob_end_clean();
		if($newsincludeoutput) {$entrynewsmain .= $newsincludeoutput;}
		eval("\$includefile = \"".gettemplate("news.includefile")."\";");
	}

	eval("\$inc[action] = \"".gettemplate("news.main")."\";");
}
?>