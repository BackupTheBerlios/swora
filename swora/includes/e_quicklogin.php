<?
///////////////////////////////
// QUICKLOGIN-HACK
///////////////////////////////
	if($innerfunction) {
		global $login;
		if(!$login[id])	{
			eval("\$catbit .= \"".gettemplate("main.quicklogin")."\";");
		} else {
			$lastlogin 					= mkdate($login[last_login]);
			if(is_allowed("any")) 				{eval("\$inc[isadmin] = \"".gettemplate("main.quicklogin_admin")."\";");}
			if($login[upload_allow] || is_allowed("user")) 	{eval("\$inc[isuploader] = \"".gettemplate("main.quicklogin_upload")."\";");}
			eval("\$catbit .= \"".gettemplate("main.quicklogin_logged")."\";");
		}
	}
?>