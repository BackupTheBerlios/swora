<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

// ----------------------- //
// User && Upload
// ----------------------- //
	if(is_allowed($sec[user][id])) {
		if(	$adminaction == "newuser_save" ||
			$adminaction == "newsletter_send" ||
			$adminaction == "edituser_save" ||
			$adminaction == "new_blockinactivate_save" ||
			$adminaction == "rights_save" ||
			$adminaction == "rights_delete" ||
			$adminaction == "blockinactiv_save" ||
			$adminaction == "rights" ||
			$adminaction == "showuser" ||
			$adminaction == "showusers" ||
			$adminaction == "blockinactiv" ||
			$adminaction == "newsletter" ||
			$adminaction == "newuser" ||
			$adminaction == "useruploader_save" ||
			$adminaction == "userupload_delete_path" ||
			$adminaction == "useruploader_showfolders" ||
			$adminaction == "useruploader"
			) {

			include("./sections/uploader/admin.php");
			include("./sections/user/admin.php");
		}
	}

// ---------------------- //
// Menu
// ---------------------- //
	if($frame=="left" && !$adminaction && !$action) {
		if(is_allowed($sec[user][id])) {
			eval("\$menu .= \"".gettemplate("admin.main.leftmenu.user")."\";");
		}
	}
?>