<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

// ----------------------- //
// Loader
// ----------------------- //
	if(is_allowed($sec[loader][id])) {
		if(	$adminaction=="loader_main" ||
			$adminaction=="loader_add" ||
			$adminaction=="loader_delete") {

			include("./sections/loader/admin.php");
		}
	}
// ---------------------- //
// Menu
// ---------------------- //
	if($frame=="left" && !$adminaction && !$action) {
		if(is_allowed($sec[loader][id])) {
			eval("\$menu .= \"".gettemplate("admin.main.leftmenu.loader")."\";");
		}
	}
?>