<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

eval("\$incf[top] = \"".gettemplate("forum.inc.top")."\";");
eval("\$incf[bottom] = \"".gettemplate("forum.inc.bottom")."\";");


$basicforum = $title;


if($boardid) 			{$thisboard	= getboard($boardid);}
if($threadid) 			{$thisthread	= getthread($threadid);}
if(!$boardid && $threadid) 	{$thisboard	= getboard($thisthread[parent_boardid]);}


/////////////////////////////////////////////////
	#############################
	if($action) {
		#######
		if($action=="markallread") {
			if($login[id]) {
				$query_str=$db->query_str("UPDATE $tab[user] SET last_forum_read='".time()."' WHERE id='$login[id]'");
				$login = checkuser();
			}
		}
		#######
		if($action=="checkpassword") {
			if(!checkboardpassword($form[boardpassword],$thisboard)) {
				eval("\$failpassword =\"".gettemplate("forum.boardpassword.wrong")."\";");
			} else {
				$boardpassword_c[$thisboard[id]] = $thisboard[board_password];
			}
		}
		#######
		if($action==nonotify) {
			if($notifyid) {
				list($threadid) = $db->query("SELECT threadid FROM $tab[forum_notify] WHERE id='$notifyid' AND userid='$login[id]'");
				if(!$thread=getthread($threadid)) {
					eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
				} else {
					if($db->query_str("DELETE FROM $tab[forum_notify] WHERE userid='$login[id]' AND id='$notifyid'"))
						eval("\$inc[action] = \"".gettemplate("forum.notify.stopped")."\";");
				}
			}
		}
		######
	}

	############################
	if($threadid || $action=="newthread" || $action=="save_thread") {
		if($boardpassword_c[$thisboard[id]]) {$form[boardpassword] = $boardpassword_c[$thisboard[id]];}
		if(($thisboard[board_password] && !checkboardpassword($form[boardpassword],$thisboard)) || $failpassword) {
			$fail = $failpassword;
			eval("\$inc[action] = \"".gettemplate("forum.boardpassword.form")."\";");
		} else {include("sections/forum/forum_thread.php");}
	}
	############################
	elseif($boardid && !$threadid) {
		if($boardpassword_c[$thisboard[id]]) {$form[boardpassword] = $boardpassword_c[$thisboard[id]];}
		if(($thisboard[board_password] && !checkboardpassword($form[boardpassword],$thisboard)) || $failpassword) {
			$fail = $failpassword;
			eval("\$inc[action] = \"".gettemplate("forum.boardpassword.form")."\";");
		} else {include("sections/forum/forum_board.php");}
	} else {
	############################
		///////////////////////
		// LIST CATS & BOARDS 
		///////////////////////
		$catsquery = $db->query_str("SELECT * FROM $tab[forum_board] WHERE is_cat='1' AND visible='1' ORDER BY sort");
		while($cat = $db->fetch_array($catsquery)) {
			unset($j,$incf[boardbit]);
			$boardsquery = $db->query_str("SELECT * FROM $tab[forum_board] WHERE is_cat='0' AND parent_boardid='$cat[id]' AND visible='1' ORDER BY sort");
			while($board = $db->fetch_array($boardsquery)) {
				//if(!($j%3) && $j) {eval("\$incf[boardbit] .= \"".gettemplate("forum.index.board.bit.tr")."\";");}

				if($board[last_postid]) 	{$lastpost = getpost($board[last_postid]);}
   				else 				{unset($lastpost[settime]);}

				if($board[board_password] && !checkboardpassword($boardpassword_c["$board[id]"],$board)) {
						eval("\$lastposting = \"".gettemplate("forum.index.board.bit.lastposting.pwd.noaccess")."\";");
				} else {
					if($board[last_userid]) {
						$lastuser		= getuser($board[last_userid]);
						$last_user_name = mkuser("user_name",$NULL,$lastuser);
						$last_time 		= mkdate($lastpost[settime]);
						eval("\$lastposting = \"".gettemplate("forum.index.board.bit.lastposting")."\";");
					} else {
						eval("\$lastposting = \"".gettemplate("forum.index.board.bit.nolastposting")."\";");
				}	}

				if($login[id] && ($lastpost[settime] >= $login[last_forum_read])  && $lastpost[aut_id]!=$login[id]) {
					$css_td="board_td_unread";$css_td_font="board_td_unread_font";
					$css_th="board_th_unread";$css_th_font="board_th_unread_font";
				} else {
					$css_td="board_td";$css_td_font="board_td_font";
					$css_th="board_th";$css_th_font="board_th_font";
				}

				if($board[board_password]) {eval("\$board[board_name].=\"".gettemplate("forum.index.boardpassword")."\";");}
				eval("\$incf[boardbit] .= \"".gettemplate("forum.index.board.bit")."\";");
				$j++;
			}
			if($cat[board_comment]) eval("\$board_comment = \"".gettemplate("forum.index.catcomment")."\";");
			eval("\$incf[catbit] .= \"".gettemplate("forum.index.cat.bit")."\";");
		}
		eval("\$incf[action] = \"".gettemplate("forum.index.cat")."\";");
	}

if(!$inc[action])
	eval("\$inc[action] = \"".gettemplate("forum.inc")."\";");

?>
