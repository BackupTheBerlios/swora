<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");



$stopheaderoutput_main = TRUE;
if(trim($info)) {
	if(!$info = $db->query("SELECT * FROM $tab[help] WHERE name='$info' OR id='$info'")) {
		eval("\$msg  = \"".gettemplate("help.nohelp")."\";");
	} else {
		$db->query_str("UPDATE $tab[help] SET views=views+1 WHERE id='$info[id]'");
		$info[text] = mksworacodes($info[text]);
		eval("\$msg  = \"".gettemplate("help.msg")."\";");
	}
	eval("\$inc[action] = \"".gettemplate("help.main")."\";");

}


?>