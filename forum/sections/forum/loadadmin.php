<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

// ----------------------- //
// Forum
// ----------------------- //
	if(is_allowed($sec[forum][id])) {
		if(	$adminaction=="forum_config" ||
			$adminaction=="forum_save_mainconfig" ||
			$adminaction=="forum_editboard" ||
			$adminaction=="forum_saveboard" ||
			$adminaction=="forum_newboard" ||
			$adminaction=="forum_boards_savesort" ||
			$adminaction=="forum_deleteboard" ||
			$adminaction=="forum_boards") {

			include("./sections/forum/admin.php");
		}
	}

// ---------------------- //
// Menu
// ---------------------- //
	if($frame=="left" && !$adminaction && !$action) {
		if(is_allowed($sec[forum][id])) {
			eval("\$menu .= \"".gettemplate("admin.main.leftmenu.forum")."\";");
		}
	}
?>