<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

########
if($adminaction=="loader_delete") {
	if(is_array($checkbox)) {
		foreach($checkbox as $a) {
			$db->query_str("DELETE FROM $tab[loader] WHERE id='$a'");
		}
	}
	$adminaction="loader_main";
}
########
if($adminaction=="loader_add") {
	if(!$form[title] || !$form[file]) {
		eval("\$fail_loader = \"".gettemplate("fail.eingabe")."\";");
	} else {
		if($db->query("SELECT * FROM $tab[loader] WHERE title='$form[title]'")) {
			eval("\$fail_loader = \"".gettemplate("fail.twice")."\";");
		} else {
			$db->query_str("INSERT INTO $tab[loader] (title,file,mode) VALUES ('$form[title]','$form[file]','$form[mode]')");
			$adminaction="loader_main";
		}
	}
}
########
if($adminaction=="loader_main" || $fail_loader) {
	$fail  = $fail_loader;

	if(!$order && 
		strtolower($order)!="title" && 
		strtolower($order)!="file" &&
		strtolower($order)!="loads" && 
		strtolower($order)!="mode") $order = "id";

	if(!$sort || strtolower($sort)!="asc" && strtolower($sort)!="desc") $sort = "desc";

	if(!$start || !is_numeric($start))	$start = 0;
	if(!$show || !is_numeric($show))	$show = 10;
	$LIMIT = " LIMIT $start,$show";

	$loadsq = $db->query_str("SELECT * FROM $tab[loader] ORDER BY $order $sort $LIMIT");
	while($load = $db->fetch_array($loadsq)) {
		if($load[mode]==1) $fungi = "Weiterleitung";
		if($load[mode]==2 || !$load[mode]) $fungi = "Einbindung";
		eval("\$loadbits .= \"".gettemplate("loader.admin.loadbit")."\";");
	}
	list($count) = $db->query("SELECT COUNT(*) FROM $tab[loader]");
	$pages = mkpages($count,$show,"loader.admin.pages.bit");
	eval("\$inc[action] = \"".gettemplate("loader.admin.main")."\";");
}
########

?>