<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

$version = "0.1";

///////////////////////////////
// Auto_Install
///////////////////////////////
	if($setupaction=="install_module") {
		if($form[tablename_board] && $form[tablename_post] && $form[tablename_notify]) {
			//////////////////////////
			// INSTALL MODULE
			//////////////////////////

				$drop1 	 	 = "DROP TABLE IF EXISTS $form[tablename_board]";
				$drop2 	 	 = "DROP TABLE IF EXISTS $form[tablename_post]";
				$drop3 	 	 = "DROP TABLE IF EXISTS $form[tablename_notify]";


				$create1 	 = "CREATE TABLE $form[tablename_board] (";
				$create1 	.= "  id int(30) NOT NULL auto_increment,";
				$create1 	.= "  parent_boardid int(30) NOT NULL default '0',";
				$create1 	.= "  is_cat int(1) NOT NULL default '0',";
				$create1 	.= "  board_name varchar(255) NOT NULL default '',";
				$create1 	.= "  board_password varchar(255) NOT NULL default '',";
				$create1 	.= "  board_comment varchar(255) NOT NULL default '',";
				$create1 	.= "  count_threads int(30) NOT NULL default '0',";
				$create1 	.= "  count_posts int(30) NOT NULL default '0',";
				$create1 	.= "  last_postid int(30) NOT NULL default '0',";
				$create1 	.= "  last_userid int(30) NOT NULL default '0',";
				$create1 	.= "  sort int(20) NOT NULL default '0',";
				$create1 	.= "  visible tinyint(1) NOT NULL default '0',";
				$create1 	.= "  PRIMARY KEY  (id)";
				$create1 	.= ")";

				$create2 	 = "CREATE TABLE $form[tablename_notify] (";
				$create2 	.= "  id int(30) NOT NULL auto_increment,";
				$create2 	.= "  userid int(30) NOT NULL default '0',";
				$create2 	.= "  threadid int(30) NOT NULL default '0',";
				$create2 	.= "  PRIMARY KEY  (id)";
				$create2 	.= ")";

				$create3 	 = "CREATE TABLE $form[tablename_post] (";
				$create3 	.= "  id int(30) NOT NULL auto_increment,";
				$create3 	.= "  parent_boardid int(30) NOT NULL default '0',";
				$create3 	.= "  parent_postid int(30) NOT NULL default '0',";
				$create3 	.= "  last_postid int(30) NOT NULL default '0',";
				$create3 	.= "  last_userid int(30) NOT NULL default '0',";
				$create3 	.= "  last_posttime int(15) NOT NULL default '0',";
				$create3 	.= "  settime int(15) NOT NULL default '0',";
				$create3 	.= "  is_first int(1) NOT NULL default '0',";
				$create3 	.= "  aut_id int(30) NOT NULL default '0',";
				$create3 	.= "  post_title varchar(255) NOT NULL default '',";
				$create3 	.= "  post_text text NOT NULL,";
				$create3 	.= "  count_views int(30) NOT NULL default '0',";
				$create3 	.= "  count_replys int(30) NOT NULL default '0',";
				$create3 	.= "  rate_points int(30) NOT NULL default '0',";
				$create3 	.= "  rate_count int(30) NOT NULL default '0',";
				$create3 	.= "  smilies int(1) NOT NULL default '0',";
				$create3 	.= "  signatur int(1) NOT NULL default '0',";
				$create3 	.= "  PRIMARY KEY  (id)";
				$create3 	.= ")";

				$deletetab1	= "DELETE FROM $tab[config] WHERE name='tab_forum_board'";
				$deletetab2	= "DELETE FROM $tab[config] WHERE name='tab_forum_post'";
				$deletetab3	= "DELETE FROM $tab[config] WHERE name='tab_forum_notify'";

				$inserttab1	= "INSERT INTO $tab[config] VALUES ('tab_forum_board','$form[tablename_board]')";
				$inserttab2	= "INSERT INTO $tab[config] VALUES ('tab_forum_post','$form[tablename_post]')";
				$inserttab3	= "INSERT INTO $tab[config] VALUES ('tab_forum_notify','$form[tablename_notify]')";

				$deleteversion  = "DELETE FROM $tab[config] WHERE name='version_forum'";
				$insertversion	= "INSERT INTO $tab[config] VALUES ('version_forum','$version')";

				$deletesection  = "DELETE FROM $tab[section] WHERE title='forum'";
				$createsection 	= "INSERT INTO $tab[section] VALUES ('','forum','Forum','0')";

				$db->query_str($drop1);
				$db->query_str($drop2);
				$db->query_str($drop3);
				$db->query_str($create1);
				$db->query_str($create2);
				$db->query_str($create3);
				$db->query_str($deletetab1);
				$db->query_str($deletetab2);
				$db->query_str($deletetab3);
				$db->query_str($inserttab1);
				$db->query_str($inserttab2);
				$db->query_str($inserttab3);
				$db->query_str($deleteversion);
				$db->query_str($insertversion);
				$db->query_str($deletesection);
				$db->query_str($createsection);

			eval("\$inc[action] = \"".gettemplate("install.forum.finished")."\";");

		} else {eval("\$fail_install = \"".gettemplate("fail.eingabe")."\";");}
	}
	#########################################
	if($setupaction=="uninstall") {
		eval("\$inc[action] = \"".gettemplate("install.forum.uninstall.ask")."\";");
	}
	#########################################
	if($setupaction=="uninstall_asked") {
		if($yes) {
			$drop1 	 	= "DROP TABLE IF EXISTS $tab[forum_board]";
			$drop2 	 	= "DROP TABLE IF EXISTS $tab[forum_post]";
			$drop3 	 	= "DROP TABLE IF EXISTS $tab[forum_notify]";

			$deletetab1	= "DELETE FROM $tab[config] WHERE name='tab_forum_board'";
			$deletetab2	= "DELETE FROM $tab[config] WHERE name='tab_forum_post'";
			$deletetab3	= "DELETE FROM $tab[config] WHERE name='tab_forum_notify'";

			$deletesec	= "DELETE FROM $tab[section] WHERE title='forum'";
			$deleteversion  = "DELETE FROM $tab[config] WHERE name='version_forum'";

			$db->query_str($deleteversion);
			$db->query_str($drop1);
			$db->query_str($drop2);
			$db->query_str($drop3);
			$db->query_str($deletetab1);
			$db->query_str($deletetab2);
			$db->query_str($deletetab3);
			$db->query_str($deletesec);

			eval("\$inc[action] = \"".gettemplate("install.forum.uninstall.finished")."\";");
		} else {header("LOCATION: index.php?section=admin&adminaction=sections");}
	}
	#########################################
	if(!$inc[action] || $fail_install) {
		$fail = $fail_install;
		eval("\$inc[action] = \"".gettemplate("install.forum")."\";");
	}
?>
