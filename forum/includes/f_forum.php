<?
#############################
function checkboardpassword($pass,$board) {
	if(!$pass) return 0;
	if(strtolower(trim($pass))==strtolower(trim($board[board_password]))) {
		setcookie( "boardpassword_c[".$board[id]."]" ,$board[board_password],time()+(3600*24*365));
		return 1;
	} else {return 0;}
}
#############################
function set_forum_notify($mail=1,$userid=-1) {
	global $form,$db,$tab,$login,$threadid,$postid,$thisthread,$thisboard,$httpurl;
	if(!$userid) $userid=$login[id];
	// DELETE NOTIFY
	$db->query_str("DELETE FROM $tab[forum_notify] WHERE userid='$userid' AND threadid='$threadid'");
	// MAKE NEW NOTIFY
	if($form[notify] && ($threadid || $postid)) {
		if(!$threadid) $threadid=$postid;
		if(!$db->query("SELECT * FROM $tab[forum_notify] WHERE userid='$userid' AND threadid='$threadid'")) {
			$db->query_str("INSERT INTO $tab[forum_notify] (userid,threadid) VALUES ('$userid','$threadid')");
		}
	}
	if($mail) forum_notify();
}
#############################
function forum_notify() {
	global $form,$db,$tab,$login,$threadid,$postid,$thisthread,$thisboard,$httpurl,$sendmail;
	$userq = $db->query_str("SELECT * FROM $tab[forum_notify] WHERE threadid='$threadid' AND userid!='$login[id]'");
	while($notify = $db->fetch_array($userq)) {
		$user = getuser($notify[userid]);

		eval("\$mail[header] = \"".gettemplate("forum.notify.mail.header")."\";");
		eval("\$mail[subject] = \"".gettemplate("forum.notify.mail.subject")."\";");
		eval("\$mail[body] = \"".gettemplate("forum.notify.mail.body")."\";");
		
		$sendmail->mail($user[user_email],$mail[subject],$mail[body],$mail[header]);
	}
}
#############################
function getpost($postid) {
	global $tab,$db;
	return ($db->query("SELECT * FROM $tab[forum_post] WHERE id='$postid'"));
}
#############################
function getboard($boardid) {
	global $tab,$db;
	return ($db->query("SELECT * FROM $tab[forum_board] WHERE id='$boardid'"));
}
#############################
function getthread($threadid) {
	global $tab,$db;
	return ($db->query("SELECT * FROM $tab[forum_post] WHERE is_first='1' AND id='$threadid'"));
}
#############################
?>