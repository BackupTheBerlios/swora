<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

if($login[id]) {
	$lastvisit 				= mkdate($login[last_avtiv]);
	if($tab[forum_post])	{list($newforum) 	= $db->query("SELECT COUNT(id) FROM $tab[forum_post] WHERE settime>='$login[last_forum_read]' AND aut_id<>'$login[id]'");} else {$newforum="N/A";}
	if($tab[pm])		{list($unreadpms) 	= $db->query("SELECT COUNT(id) FROM $tab[pm] WHERE inbox='1' AND toid='$login[id]' AND view='0'");} else {$unreadpms="N/A";}
	if($login[rate_count]) 	{$rating = mkstatus($login[rate_points],$login[rate_count]);$rount_number=round($login[rate_points]/$login[rate_count],4);}


	if(!$login[upload_allow]) { $upload_paths = "---"; }
	else {			$ftpq = $db->query_str("SELECT * FROM $tab[upload_access] WHERE userid='$login[id]'");
				while($set = $db->fetch_array($ftpq)) {
					$upload_path[] = $set[path];
				}
				if(is_array($upload_path)) {
					$upload_paths = implode("<br>",$upload_path);
				}
	}

	$rightsq =		$db->query_str("SELECT * FROM $tab[right] WHERE userid='$login[id]'");
				while($oright = $db->fetch_array($rightsq)) {
					if($oright[is_admin]) 	{$rights[] = "Admin"; break;}
					else 			{$rights[] = $sec[$oright[section]][name];}
				}
				if(is_array($rights)) 	{$rights = implode("<br>",$rights);}
				else 			{$rights = "----";}

	eval("\$entrynewsmain 	= \"".gettemplate("user.stats")."\";");
} else {
	eval("\$entrynewsmain 	= \"".gettemplate("loginform")."\";");
}
?>