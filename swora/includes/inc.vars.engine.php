<?
///////////////////////////////
// TEMPLATE VARS
///////////////////////////////
	eval("\$catspace = \"".gettemplate("include.main.menu.space")."\";");

/////////////////////////////////////////
//	Default Engine
/////////////////////////////////////////
// ......


//////////////////////////////
// ONLINE MESSAGE
//////////////////////////////
	if($login[id]) {
		//////////////////////////////
		// SAVE THE NEW OMSG
		//////////////////////////////
		if($saveom) {
			$stopheaderoutput_main = 1;
			if($saveom=="username") {
				$user = $db->query("SELECT id FROM $tab[user] WHERE id='$form[target]' OR user_name='$form[target]' OR user_login='$form[target]' LIMIT 1");
			} else {
				$user[id] = $userid;
			}
			if(!$user[id] || ($user[id] == $login[id])) {
				$form[text] = stripslashes($form[text]);
				eval("\$fail_newom = \"".gettemplate("fail.invalid.username")."\";");
			} else {
				$db->query_str("INSERT INTO $tab[onlinemessage] (aut_id,time,touserid,text,send) VALUES ('$login[id]','".time()."','$user[id]','$form[text]','0')");
				unset($newom);
				eval("\$inc[action] = \"".gettemplate("onlinemessage.closepopup")."\";");
			}
			
		}
		//////////////////////////////
		// DO AN NEW OMSG
		//////////////////////////////
		if($newom || $fail_newom) {
			$fail = $fail_newom;
			$stopheaderoutput_main = 1;
			$user_name = mkuser("user_name",0,$login);
			if(!$userid) {
				eval("\$inc[action] = \"".gettemplate("onlinemessage.new.nonuserid")."\";");
			} else {
				if($userid==$login[id]) {
					eval("\$inc[action] = \"".gettemplate("onlinemessage.closepopup")."\";");
				} else {
					$target = mkuser("user_name",$userid,$NULL);
					eval("\$inc[action] = \"".gettemplate("onlinemessage.new.userid")."\";");
				}
			}
		}
		//////////////////////////////
		// MARK READ
		//////////////////////////////
		if($setomsend) {
			$stopheaderoutput_main = 1;
			$id_array = explode("|",$setomsend);
			$id = implode("' OR id='",$id_array);
			$db->query_str("UPDATE $tab[onlinemessage] SET send='1' WHERE touserid='$login[id]' AND (id='$id')");
			eval("\$inc[action] = \"".gettemplate("onlinemessage.closepopup")."\";");
		} else {
			//////////////////////////////
			// SHOW A OMSG
			//////////////////////////////
			$omq = $db->query_str("SELECT id,aut_id,text FROM $tab[onlinemessage] WHERE touserid='$login[id]' AND send='0'");
			if(mysql_num_rows($omq)) {
				while($msg = $db->fetch_array($omq)) {
					$i++;
					$user = getuser($msg[aut_id]);
					$msg[text] = str_replace("\r\n","\\r\\n",str_replace("'","\"",$msg[text]));
					eval("\$jsvar .= \"".gettemplate("onlinemessage.alertbox")."\";");
					$ids[] = $msg[id];
					$strs .= "str_$i + ";
				}

				$ids = "'".implode("|",$ids)."'";
				eval("\$OnlineMessage = \"".gettemplate("onlinemessage.javascript")."\";");
				$onload = " onload='OnlineMessage()'";unset($i);
			}
		}
	}

############################

/***********************************************************************************
 *	HIER NICHTS MEHR ÄNDERN							   *
 ***********************************************************************************/


///////////////////////////////
// MENU VARS
///////////////////////////////
	if($config[e_menu]) {
		eval("\$inc[menutop] 	= getmenu(0);");
		eval("\$inc[menuleft] 	= getmenu(1);");
		eval("\$inc[menuright] 	= getmenu(2);");
		eval("\$inc[menubottom] = getmenu(3);");
	}

///////////////////////////////
// TEMPLATE VARS II
///////////////////////////////
	eval("\$inc[maintable] = \"".gettemplate("include.main.maintable")."\";");
	eval("\$inc[maintableclose] = \"".gettemplate("include.main.maintableclose")."\";");
?>