<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

if(!$boardid) {
	eval("\$incf[action] = \"".gettemplate("forum.wrongid")."\";");
} else {
	////////////////////////////
	// Blättern
	////////////////////////////
	if(!$start)  $start = "0";
	if(!$showpp) $showpp = $config[forum_show_threadspp];
	$counthreads = $db->query("SELECT COUNT(*) FROM $tab[forum_post] WHERE parent_boardid='$boardid' AND is_first='1'"); $counthreads = $counthreads[0];
	if($counthreads > $config[forum_show_threadspp]) {
		while($counthreads > 0) {
			$counthreads -= $config[forum_show_threadspp];
			if($started) {$number++;$startnow += $config[forum_show_threadspp];
			} else { $started++;$number = "1";$startnow = "0";}
			eval("\$inc[showpp] .= \"".gettemplate("forum.showpp.threads.num")."\";");
		}
	}
	///////////////////////////
	$boardquery = $db->query_str("SELECT * FROM $tab[forum_post] WHERE parent_boardid='$boardid' AND is_first='1' ORDER BY last_posttime DESC,settime DESC LIMIT $start,$showpp");
	if(!mysql_num_rows($boardquery)) {
		eval("\$inc[verzeichnis] = \"".gettemplate("forum.board.verzeichnis")."\";");
		eval("\$incf[threads] = \"".gettemplate("forum.board.nothreads")."\";");
	} else {
		////////////////////////////
		// LIST THREADS
		////////////////////////////
		while($thread = $db->fetch_array($boardquery)) {
			unset($inc[showpp]);
			$threadcolor++;
			$countposts 		= $thread[count_replys]+1;
			$user_name 		= mkuser("user_name",$thread[aut_id],$NULL);
			$last_user_name 	= ($thread[last_userid]) ? mkuser("user_name",$thread[last_userid],$NULL) : $user_name;

			if($countposts > $config[forum_show_postspp]) {
				while(	$countposts > 0) {
					$countposts -= $config[forum_show_postspp];
					if($started) {
						$number++;
						$startnow += $config[forum_show_postspp];
					} else { 
						$started++;
						$number = "1";
						$startnow = "0";
					}
					$threadid = $thread[id];
					eval("\$inc[showpp] .= \"".gettemplate("forum.showpp.posts.num")."\";");
				}
			}
			unset($started,$number,$startnow,$countposts);

			if($thread[last_posttime])
				$lastdate = 	mkdate($thread[last_posttime]);
			else 	$lastdate = 	mkdate($thread[settime]);
			if(($thread[last_posttime] <= $login[last_forum_read])) {
				if($threadcolor % 2 =="0")	$csstyle="2";
				else 				$csstyle="1";
			} else {
				if($threadcolor % 2 =="0")	$csstyle="4";
				else 				$csstyle="3";
			}
			eval("\$incf[threadbit] .= \"".gettemplate("forum.board.thread.bit")."\";");
		}
		eval("\$inc[verzeichnis] = \"".gettemplate("forum.board.verzeichnis")."\";");
		eval("\$incf[threads] = \"".gettemplate("forum.board.thread")."\";");
	}
	eval("\$incf[action] = \"".gettemplate("forum.board")."\";");
}
?>