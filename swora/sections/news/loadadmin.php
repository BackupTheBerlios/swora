<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

// ----------------------- //
// NEWS
// ----------------------- //
	if(is_allowed($sec[news][id])) {
			if(	$adminaction=="news_new" || 
				$adminaction=="news_select" ||
				$adminaction=="new_new_save" ||
				$adminaction=="shownewsdetails" ||
				$adminaction=="news_edit_save" ||
				$adminaction=="news_activate" || 
				$adminaction=="delcomment" ||
				$adminaction=="news_config" ||
				$adminaction=="news_main_config_save" ||
				$adminaction=="news_edit") {

				include("./sections/news/admin.php");
		}
	}

// ---------------------- //
// Menu
// ---------------------- //
	if($frame=="left" && !$adminaction && !$action) {
		if(is_allowed($sec[news][id])) {
			eval("\$menu .= \"".gettemplate("admin.main.leftmenu.news")."\";");
		}
	}
?>