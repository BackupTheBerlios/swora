<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

// ----------------------- //
// Swora Engine
// ----------------------- //
	if(is_allowed($sec[swora][id])) {
		if(	$adminaction == "save_mainconfig" 	||
			$adminaction == "mainconfig" 		||
			$adminaction == "save_htmlcode" 	||
			$adminaction == "htmlcode" 		||
			$adminaction == "menu_new_save" 	||
			$adminaction == "menu_new" 		||
			$adminaction == "menu_edit_save" 	||
			$adminaction == "link_edit" 		||
			$adminaction == "save_menu_sort" 	||
			$adminaction == "link_delete" 		||
			$adminaction == "menu_edit" 		||
			$adminaction == "save_smilies" 		||
			$adminaction == "delete_smilies" 	||
			$adminaction == "smilies" 		||
			$adminaction == "save_section" 		||
			$adminaction == "do_section" 		||
			$adminaction == "sections" 		||
			$adminaction == "section_edit" 		||
			$adminaction == "save_edit_section" 	||
			$adminaction == "points_save" 		||
			$adminaction == "points" 		||
			$adminaction == "error_delete" 		||
			$adminaction == "showerror" 		||
			$adminaction == "errors" 		||
			$adminaction == "save_engine" 		||
			$adminaction == "engine" 		||
			$adminaction == "auto_install_start" 	||
			$adminaction == "traffic" 		||
			$adminaction == "save_menu_enabled" 	||
			$adminaction == "tables" 		||
			$adminaction == "edittable" 		||
			$adminaction == "deletetable" 		||
			$adminaction == "backuptable" 		||
			$adminaction == "renametable" 		||
			$adminaction == "newtable" 		||
			$adminaction == "new_hack" 		||
			$adminaction == "install_hack" 		||
			$adminaction == "config_hack" 		||
			$adminaction == "bannes" 		||
			$adminaction == "save_bannes" 		||
			$adminaction == "ftps" 			||
			$adminaction == "save_ftp" 		||
			$adminaction == "delete_ftp" 		||
			$adminaction == "edit_ftp" 		||
			$adminaction == "save_edit_ftp" 	||
			$adminaction == "uninstall_hack"	||
			$adminaction == "update_eval"		||
			$adminaction == "update"		||
			$adminaction == "help"			||
			$adminaction == "help_delete"		||
			$adminaction == "help_edit"		||
			$adminaction == "help_save"		||
			$adminaction == "help_new"		||
			$adminaction == "limittraffic"		||
			$adminaction == "limittraffic_save"	||
			$adminaction == "limittraffic_delete"	||
			$adminaction == "limittraffic_activ"	||
			$adminaction == "limittraffic_edit"	||
			$adminaction == "limittraffic_edit_save"	
			) {
			include("./sections/swora/swora.php");
		}
	}

// ---------------------- //
// Menu
// ---------------------- //
	if($frame=="left" && !$adminaction && !$action) {
		if(is_allowed($sec[swora][id])) {
			eval("\$menu .= \"".gettemplate("admin.main.leftmenu.swora")."\";");
		}
	}
?>