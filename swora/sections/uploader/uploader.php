<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

if($action && ($serverid || $setid)) {
	######################
	if(is_allowed_upload()!="mod") {
		$set 			= $db->query("SELECT * FROM $tab[upload_access] WHERE id='$setid'");
		$serverid 		= $set[serverid];
	}

	#####################
	if($action=="new_dir") {
		if(!$form[newdir]) {eval("\$fail_newdir = \"".gettemplate("fail.eingabe")."\";");}
		if($form[basedir][0] != "/") $form[basedir] = "/".$form[basedir];
		if($form[basedir][strlen($form[basedir])-1]!="/") $form[basedir].="/";

		$accesscodes = getftpaccesscodes($serverid);
		if(!is_allowed_upload($serverid,$form[basedir])) {
			eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd])) {
				if($ftp->cchdir($form[basedir])) {
						if(!$ftp->cmkdir($form[newdir])) {
							eval("\$fail_newdir = \"Directory just exists.\";");
						}
				} else {
					eval("\$fail_newdir = \"Couldn't Change Working Directory.\";");
				}
				$ftp->disconnect();
			} else {
				eval("\$fail_newdir = \"Couldn't Connect to Server.\";");
			}
		}
	}
	#####################
	if($action=="new_file") {
		if($form[basedir][0] != "/") $form[basedir] = "/".$form[basedir];
		if($form[basedir][strlen($form[basedir])-1]!="/") $form[basedir].="/";

		$accesscodes = getftpaccesscodes($serverid);
		if(!is_allowed_upload($serverid,$form[basedir])) {
			eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			$file = $HTTP_POST_FILES[upfile];
			if(is_file($file[tmp_name])) {
				if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd])) {
					if($ftp->csize($form[basedir]."/".$file[name])!=-1 && !$form[overwrite]) {
						eval("\$fail_newfile = \"".gettemplate("fail.upload.fileexists")."\";");
					} else {
						$fp = @fopen($file[tmp_name],"r");
						if(!$ftp->cfput($fp,$form[basedir].$file[name])) {
							eval("\$fail_newfile = \"".gettemplate("fail.upload.cant")."\";");
						}
						@fclose($fp);
					}
					$ftp->disconnect();
				} else {
					eval("\$fail_newfile = \"Couldn't Connect to Server.\";");
				}
			} else {
				eval("\$fail_newfile = \"".gettemplate("fail.upload.invalidfile")."\";");
			}
		}
	}
	#####################
	if($action=="del_dir") {
		if($form[deldir][0] != "/") $form[deldir] = "/".$form[deldir];
		if($form[deldir][strlen($form[deldir])-1]!="/") $form[deldir].="/";

		$accesscodes = getftpaccesscodes($serverid);
		if(!is_allowed_upload($serverid,$form[deldir])) {
			eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd])) {
				if(!rekursive_del_dir($form[deldir])) {
					eval("\$fail_delfile = \"Couldn't delete Folder.\";");
				}
				$ftp->disconnect();
			} else {
				eval("\$fail_delfile = \"Couldn't Connect to Server.\";");
			}
		}
	}
	#####################
	if($action=="rename_file") {
		if($form[basedir][0] != "/") $form[basedir] = "/".$form[basedir];
		if($form[basedir][strlen($form[basedir])-1]!="/") $form[basedir].="/";

		$accesscodes = getftpaccesscodes($serverid);
		if(!is_allowed_upload($serverid,$form[basedir])) {
			eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd])) {
				if($ftp->cchdir($form[basedir])) {
					if(!$ftp->crename($form[basefile],$form[renamefile])) {
						eval("\$fail_mvfile = \"Couldn't Rename.\";");
					}
				} else {
					eval("\$fail_mvfile = \"Couldn't Change Directory.\";");
				}
				$ftp->disconnect();
			} else {
				eval("\$fail_mvfile = \"Couldn't Connect to Server.\";");
			}
		}
	}
	#####################
	if($action=="del_file") {
		if($form[basedir][0] != "/") $form[basedir] = "/".$form[basedir];
		if($form[basedir][strlen($form[basedir])-1]!="/") $form[basedir].="/";

		$accesscodes = getftpaccesscodes($serverid);
		if(!is_allowed_upload($serverid,$form[basedir])) {
			eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd])) {
				if($ftp->cchdir($form[basedir])) {
					if(!$ftp->cdelete($form[delfile])) {
						eval("\$fail_delfile = \"Couldn't Delete.\";");
					}
				} else {
					eval("\$fail_delfile = \"Couldn't Change Directory.\";");
				}
				$ftp->disconnect();
			} else {
				eval("\$fail_delfile = \"Couldn't Connect to Server.\";");
			}
		}
	}
	#####################
}
##########################################
##########################################
##########################################
##########################################
if($serverid || $setid || $fail_upload) {
	$fail = $fail_upload;
	$GRANTACCESS = FALSE;
	$ftp -> disconnect();

	if(is_allowed_upload()=="mod") {
		$accesscodes 	= getftpaccesscodes($serverid);
		if(!$spath) 	{$load = $accesscodes[path];}
		else 		{$load = $spath;}
		$GRANTACCESS 	= TRUE;
	} else {
		if(!$serveridchanged) {$set = $db->query("SELECT * FROM $tab[upload_access] WHERE id='$setid'");}
		$accesscodes 	= getftpaccesscodes($set[serverid]);
		if($accesscodes[path][strlen($accesscodes[path])-1]=="/")$accesscodes[path] = substr($accesscodes[path],0,strlen($accesscodes[path])-1);
		if(!$spath) 	{$load = $accesscodes[path].$set[path];}
		else 		{$load = $spath;}
		if(is_allowed_upload($set[serverid],$load)) {$GRANTACCESS = TRUE;}
	}

	if(!$GRANTACCESS) {
		eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
	} else {
		if($ftp->connect($accesscodes[host],$accesscodes[port],$accesscodes[user],$accesscodes[pwd]))  {
			if($load[0]=="/") $load = substr($load,1,strlen($load));
			if(trim($load)!="") $ftp->cchdir($load);
			if(1) {
				$pwd = $ftp->cpwd();
				$folderfiles = rekursive_load_dirs_ftp($accesscodes[path].$set[path],$pwd);
				eval("\$inc[action] = \"".gettemplate("uploader.main")."\";");
			} else {
				eval("\$fail_connect = \"Coudn't locate Ftp.\";");
			}
		} else {
			eval("\$fail_connect = \"Coudn't connect to Ftp.\";");
		}
	}
}
#########################################
if((!$serverid && !$setid)|| !$inc[action] || $fail_connect) {
	$fail = $fail_connect;
	if(!is_allowed_upload()) {
		eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
	} else {
		if(is_allowed_upload()=="mod") {
			$serverq = $db->query_str("SELECT * FROM $tab[ftp]");
			while($server = $db->fetch_array($serverq)) {
				eval("\$serverlist .= \"".gettemplate("uploader.server.bit")."\";");
			}
			if(!$serverlist) {eval("\$serverlist = \"".gettemplate("uploader.server.nonbit")."\";");}
			eval("\$inc[action] = \"".gettemplate("uploader.server.main")."\";");
		} else {
			$fquery = $db->query_str("SELECT * FROM $tab[upload_access] WHERE userid='$login[id]'");
			while($set = $db->fetch_array($fquery)) {
				if($set[serverid]) {
					$server = getftpaccesscodes($set[serverid]);
					if(is_array($server)) {
						$server[id]="";
						eval("\$serverlist .= \"".gettemplate("uploader.server.bit")."\";");
					}
				}
			}
			if(!$serverlist) {eval("\$serverlist = \"".gettemplate("uploader.server.nonbit")."\";");}
			eval("\$inc[action] = \"".gettemplate("uploader.server.main")."\";");
		}
	}
}
#########################################
?>