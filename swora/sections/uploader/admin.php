<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

#######################################
if($adminaction=="useruploader_save") {
	if($form[userid]) {
		$user = getuser($form[userid]);
		if($form[upload_disallow]) {
			if($result = $db->query("SELECT * FROM $tab[useroption] WHERE userid='$form[userid]' AND `name`='upload_allow'"))
				$db->query_str("UPDATE $tab[useroption] SET value='0' WHERE id='$result[id]'");
			else 	$db->query_str("INSERT INTO $tab[useroption] (userid,name,value) VALUES ('$form[userid]','upload_allow','0')");
		}
		elseif($form[upload_allow]) {
			if($result = $db->query("SELECT * FROM $tab[useroption] WHERE userid='$form[userid]' AND `name`='upload_allow'"))
				$db->query_str("UPDATE $tab[useroption] SET value='1' WHERE id='$result[id]'");
			else 	$db->query_str("INSERT INTO $tab[useroption] (userid,name,value) VALUES ('$form[userid]','upload_allow','1')");
		}

		if($form[newfree]) {
			if($form[upload_paths][0]!="/") $form[upload_paths] = "/".$form[upload_paths];
			if($form[upload_paths][strlen($form[upload_paths])-1]!="/") $form[upload_paths].="/";

			$db->query_str("INSERT INTO $tab[upload_access] (userid,serverid,path) VALUES ('$form[userid]','$form[ftpid]','$form[upload_paths]')");
		}
	}
	$adminaction="useruploader";
}
#######################################
if($adminaction=="userupload_delete_path") {
	if($form[path] && $userid) {
		$db->query_str("DELETE FROM $tab[upload_access] WHERE id='$form[path]'");
	}
	$adminaction="useruploader_showfolders";
}
#######################################
#######################################
#######################################
if($adminaction=="useruploader_showfolders") {
	if($userid) {
		$user = getuser($userid,1);
		$fquery = $db->query_str("SELECT * FROM $tab[upload_access] WHERE userid='$user[id]'");
		while($set = $db->fetch_array($fquery)) {
			$server = getftpaccesscodes($set[serverid]);
			eval("\$paths.=\"".gettemplate("uploader.admin.useruploader.showfolders.folderbit")."\";");
		}
		eval("\$inc[action] = \"".gettemplate("uploader.admin.useruploader.showfolders")."\";");
	}
}
#######################################
if($adminaction=="useruploader" || $fail_upload) {
	$fail = $fail_upload;
	$ftpq  = $db->query_str("SELECT * FROM $tab[ftp]");
	while($server = $db->fetch_array($ftpq)) {
		eval("\$ftplist .= \"".gettemplate("uploader.admin.useruploader.ftpbit")."\";");
	}
	$query = $db->query_str("SELECT * FROM $tab[user] WHERE activated='1' and blocked='0'");
	while($user = $db->fetch_array($query)) {
		$bgcolor=($db->query("SELECT id FROM $tab[useroption] WHERE userid='$user[id]' AND name='upload_allow' AND value='1'"))?"00ff00":"ff0000";
		eval("\$userbits .= \"".gettemplate("uploader.admin.useruploader.userbit")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("uploader.admin.useruploader.main")."\";");
}
#######################################

?>