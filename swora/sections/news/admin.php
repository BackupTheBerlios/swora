<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");


################
if($adminaction=="new_new_save") {
	$user 		= getuser($login[id]);
	$time 		= time();
	$testquery 	= $db->query("SELECT * FROM $tab[news] WHERE title='$form[title]' AND text='$form[text]'");
	if($testquery) {eval("\$fail_new = \"".gettemplate("news.fail.newstwice")."\";");}
	else {
		if($form[admin_puplic] && is_allowed($sec[news][id]))  $puplic="1";
		else $puplic="0";
		$savenewsquery = $db->query_str("INSERT INTO $tab[news] (autid,date,title,text,smilies,html,activated) 
						VALUES ('$login[id]','$time','$form[title]','$form[text]','$form[smilies]','$form[html]','$puplic')");		
		eval("\$fail_new = \"".gettemplate("news.success.send")."\";");
		unset($form);
	}
}
###########################
if($adminaction=="news_main_config_save") {
	foreach($form as $name=>$value) {
		if($name=="news_include_others" && ($value && !is_file($value))){eval("\$fail_config = \"".gettemplate("fail.upload.invalidfile")."\";");}

		$db->query_str("DELETE FROM $tab[config] WHERE name='$name'");
		$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$value','$name')");
	}
	$config 	= getconfig();
	$adminaction	= "news_config";
}
###########################
if($adminaction=="news_edit_save") {
	$time 		= time();
	$savenewsquery 	= $db->query_str("UPDATE $tab[news] SET title='$form[title]', text='$form[text]', smilies='$form[smilies]', html='$form[html]' WHERE id='$newsid'");
	$adminaction	= "shownewsdetails";
}
############################
if($adminaction=="delcomment") {
	$delquery		= $db->query_str("DELETE FROM $tab[news_comment] WHERE id='$commentid'");
	$adminaction_comments 	= 1;
}
############################
if($adminaction_delete) {
	$delquery 	= $db->query_str("DELETE FROM $tab[news] WHERE id='$newsid'");
	$adminaction	= "news_select";
}
############################
if($adminaction_block) {
	$block = $db->query("SELECT * FROM $tab[news] WHERE id='$newsid'");
	$block[blocked] ? $block	= 0 
			: $block	= 1;
	$savequery 	= $db->query_str("UPDATE $tab[news] SET blocked='$block' WHERE id='$newsid'");
	header("LOCATION: $HTTP_REFERER");
}
############################
if($adminaction_in_activate) {
	$news = $db->query("SELECT * FROM $tab[news] WHERE id='$newsid'");
	$news[activated] ? $activ	= 0 
			 : $activ	= 1;
	if($activ) 	 addpoints($points[news_activate], $news[autid]);
	$savequery 	= $db->query_str("UPDATE $tab[news] SET activated='$activ' WHERE id='$newsid'");
	header("LOCATION: $HTTP_REFERER");
}
############################









###########################
if($adminaction_edit) {
		$form 		= $db->query("SELECT * FROM $tab[news] WHERE id='$newsid'");
		$user_name 	= mkuser("user_name",$form[autid],$NULL);
		$smilies 	= getsmiliesbit("news.newsform.smilie.bit");
		$thisaction	= "news_edit_save";
		unset($adminaction);
		eval("\$inc[action] = 	\"".gettemplate("news.admin.newsform")."\";");
}
############################
if($adminaction_comments) {
	unset($adminaction);
	$comquery 	= $db->query_str("SELECT * FROM $tab[news_comment] WHERE newsid='$newsid'");
	if($comquery) {
		while($com = $db->fetch_array($comquery)) {
			$com[date] = mkdate($com[date]);
			eval("\$inc[combit] .= \"".gettemplate("news.admin.comment.bit")."\";");
		}
		if(!mysql_num_rows($comquery)) {
			eval("\$inc[combit] = \"".gettemplate("news.comment.nocoms")."\";");
		}
	}
	eval("\$inc[action] = \"".gettemplate("news.admin.comment")."\";");
}
############################
if($adminaction=="shownewsdetails") {
	$news 	= $db->query("SELECT * FROM $tab[news] WHERE id='$newsid'");
	if(!$news) {eval("\$inc[action] = \"".gettemplate("news.edit.fail.wrongnewsid")."\";");}
	else {
		$aut 		= getuser($news[autid]);
		$news[text] 	= htmlspecialchars($news[text]);
		$news[text] 	= str_replace("\n","<br>",$news[text]);
		$news[date] 	= mkdate($news[date]);
		$news[activated] ? $activ	= "inactivate"
				 : $activ	= "activate";
		$news[blocked]   ? $block	= "unblock" 
				 : $block	= "block";

		eval("\$subactioncontrol = \"".gettemplate("news.edit.subactions")."\";");
		eval("\$inc[action] = \"".gettemplate("news.edit.selectsubaction")."\";");
	}
}
############################
if($adminaction=="news_select") {
	$newsquery = $db->query_str("SELECT * FROM $tab[news] ORDER BY date DESC");
	while($news = $db->fetch_array($newsquery)) {
		unset($blocked,$activated);
		if(!$news[title])	$news[title]	= "----";
		if($news[blocked]) 	$blocked	= "blocked";
		if(!$news[activated]) 	$activated	= "inactiv";
		$countcoms 		= $db->query("SELECT COUNT(*) FROM $tab[news_comment] WHERE newsid='$news[id]'");
		$comments		= $countcoms[0];
		eval("\$inc[newsselect] .= \"".gettemplate("news.edit.selectnews.bit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("news.edit.selectnews")."\";");
 }
############################
if($adminaction=="news_new" || $fail_new) {
	if(!$login) {
		eval("\$inc[action] = \"".gettemplate("fail.access.notloggedin")."\";");
	} else {
		$fail 		= $fail_new;
		$user 		= getuser($login[id]);
		$smilies 	= getsmiliesbit("news.newsform.smilie.bit");
		$user_name 	= mkuser("user_name",0,$login);
		$thisaction 	= "new_new_save";
		eval("\$admin 	= \"".gettemplate("news.newsform.admin")."\";");

		eval("\$inc[action] = 	\"".gettemplate("news.admin.newsform")."\";");
	}
}
################
if($adminaction=="news_config" || $fail_config) {
	$fail 	= $fail_config;
	eval("\$inc[action] = \"".gettemplate("news.admin.config.main")."\";");
}
################
?>