<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");


################
if($adminaction=="forum_boards_savesort") {
	$db->query_str("UPDATE $tab[forum_board] SET sort='$value' WHERE id='$id'");
	$adminaction	= "forum_boards";
}
################
if($adminaction=="forum_saveboard") {
	unset($coun);
	$query_str 	= $db->query_str("UPDATE $tab[forum_board] SET
						is_cat='$form[is_cat]',
						parent_boardid='$form[parent_boardid]',
						board_name='$form[board_name]',
						board_password='$form[board_password]',
						board_comment='$form[board_comment]',
						visible='$form[visible]'
						WHERE id='$boardid'");
	$adminaction	= "forum_boards";
}
################
if($adminaction=="forum_deleteboard") {
	foreach($boardcheckbox as $a=>$b) {
		$board 	= getboard($a);
		if($board[is_cat]) {
			$todelboardsq = $db->query_str("SELECT * FROM $tab[forum_board] WHERE is_cat='0' AND parent_boardid='$a'");
			while($todelboards = $db->fetch_array($todelboardsq)) {
				$delete = 	$db->query_str("DELETE FROM $tab[forum_post] WHERE parent_boardid='$todelboards[id]'");
				$deleteposts = 	$db->query_str("DELETE FROM $tab[forum_board] WHERE parent_boardid='$todelboards[id]'");
			}
			$delcat = $db->query_str("DELETE FROM $tab[forum_board] WHERE is_cat='1' AND id='$a'");
		} else {
			$delete = $db->query_str("DELETE FROM $tab[forum_board] WHERE id='$a'");
			$deleteposts = $db->query_str("DELETE FROM $tab[forum_board] WHERE parent_boardid='$a'");
		}
	}
	$adminaction	= "forum_boards";
}
################
if($adminaction=="forum_newboard") {
	$query_str 	= $db->query_str("INSERT INTO $tab[forum_board] (parent_boardid,is_cat,board_name,board_password,board_comment,visible) VALUES ('$form[parent_boardid]','$form[is_cat]','$form[board_name]','$form[board_password]','$form[board_comment]','$form[visible]')");
	$adminaction 	= "forum_boards";
}
################
if($adminaction=="forum_save_mainconfig") {
	$db->query_str("DELETE FROM $tab[config] WHERE name='forum_show_threadspp'");
	$db->query_str("DELETE FROM $tab[config] WHERE name='forum_show_postspp'");
	$db->query_str("DELETE FROM $tab[config] WHERE name='forum_guestpost'");
	$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$form[forum_show_threadspp]','forum_show_threadspp')");
	$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$form[forum_show_postspp]','forum_show_postspp')");
	$db->query_str("INSERT INTO $tab[config] (value,name) VALUES ('$form[forum_guestpost]','forum_guestpost')");


	$config 	= getconfig();
	$adminaction	= "forum_config";			
	unset($form);
}







################
if($adminaction=="forum_config") {
	if($config[forum_guestpost]) $gpost[on] = " selected";else $gpost[off] = " selected";
	eval("\$inc[action] = \"".gettemplate("forum.admin.mainconfig")."\";");
}
################
if($adminaction=="forum_boards") {
	$catquery = $db->query_str("SELECT * FROM $tab[forum_board] WHERE is_cat='1' ORDER BY sort");
	while($cat = $db->fetch_array($catquery)) {
		for($i=0;$i<=15;$i++) {unset(${"c".$i});}unset($c);
		$c = "c".$cat[sort];$$c = " selected";
		eval("\$cats = \"".gettemplate("forum.admin.cat.bit")."\";");
		$boardquery 	= $db->query_str("SELECT * FROM $tab[forum_board] WHERE parent_boardid='$cat[id]' AND is_cat='0' ORDER BY sort");
		while($board 	= $db->fetch_array($boardquery)) {
			for($i=0;$i<=15;$i++) { unset(${"b$i"}); }unset($m);
			$m = "b".$board[sort];$$m = " selected";
			eval("\$boards .= \"".gettemplate("forum.admin.board.bit")."\";");
		}
		eval("\$inc[boards] .= \"".gettemplate("forum.admin.listboards")."\";");
		unset($cats);unset($boards);
	}
	$catlistq = $db->query_str("SELECT * FROM $tab[forum_board] WHERE is_cat='1'ORDER BY id");
	while($catl = $db->fetch_array($catlistq)) {
		eval("\$catlist .= \"".gettemplate("forum.admin.cats.list.select")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("forum.admin.boards")."\";");
}
################
if($adminaction=="forum_editboard") {
	$board = getboard($boardid);
	if($board[is_cat]) 	$ocat 		= " selected";
	else 		    	$oboard 	= " selected";
	if(!$board[visible]) 	$vis 		= " unchecked";
	else 			$vis 		= " checked";
	$catlistq 	= $db->query_str("SELECT * FROM $tab[forum_board] WHERE is_cat='1'ORDER BY id");
	while($catl 	= $db->fetch_array($catlistq)) {
		unset($selected);
		if($catl[id] == $board[parent_boardid]) $selected = " selected";
		eval("\$catlist .= \"".gettemplate("forum.admin.cats.list.select")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("forum.admin.board.edit")."\";");
}
################
?>