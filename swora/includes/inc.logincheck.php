<?
$cookie_user_id 		= $HTTP_COOKIE_VARS["cookie_user_id"];
$cookie_user_password 	= $HTTP_COOKIE_VARS["cookie_user_password"];
$loginsession			= $HTTP_SESSION_VARS["loginsession"];
$login					= FALSE;


if((($loginform[username]
	&& $loginform[userpassword])
	|| $loginsession
	|| ($cookie_user_id
	&& $cookie_user_password))) {
	///////////////////////////////////
	// PREPARE
	///////////////////////////////////
		if(!$loginform) {
			if($loginsession) {
				$loginform[username] = 		$loginsession[user_login];
 				$loginform[userpassword] = 	$loginsession[user_password];
			}
			elseif(($cookie_user_id && $cookie_user_password)) {
				$user = 			getuser($cookie_user_id);
				$loginform[username] = 		$user[user_login];
		 		$loginform[userpassword] = 	$cookie_user_password;
			}
		}
	///////////////////////////////////
	// CHECK
	///////////////////////////////////
		$login = checkuser($logout);

	///////////////////////////////////
	// LOGIN SUCCESSFULL ??????
	///////////////////////////////////
		if(!is_array($login)) {
			#######LOGIN FAILED#######
			if($login=="1") 	{eval( "\$inc[action] = \"". gettemplate("fail.login.noexistinguser") ."\";" );}
			elseif($login=="2") 	{eval( "\$inc[action] = \"". gettemplate("fail.login.blockeduser") ."\";");}
			elseif($login=="3") 	{eval( "\$inc[action] = \"". gettemplate("fail.login.wrongpassword") ."\";" );}
			elseif($login=="4") 	{eval( "\$inc[action] = \"". gettemplate("fail.login.usernotactiv") ."\";" );}
			elseif($login=="5") 	{eval( "\$inc[action] = \"". gettemplate("logout") ."\";" );}
			unset($login);
			########################
		} else {
			/////////////////////////
			// SAVE SESSIONVARS + COOKIE
			/////////////////////////
				$loginsession = 		$login;
				$loginsession[user_password] = 	$loginform[userpassword];
				session_register( loginsession );

				$cookietime = time()+(3600*24*365);

				setcookie( "cookie_user_id" ,		"$login[id]" , 			$cookietime);
				setcookie( "cookie_user_password" , 	"$loginform[userpassword]" , 	$cookietime);
		}
}


////////////////////////////////
// CHECK FUNKTION
////////////////////////////////
function checkuser($logout="0") {
	global $tab,$db,$loginform,$dothelogin;

	foreach($loginform as $a=>$b) $loginform[$a] = trim($b);

	if($logout) {
		session_unset( loginsession );
		setcookie( "cookie_user_id" ,"", -1);
		setcookie( "cookie_user_password" ,"" , -1);
		return 5;
	}

	##################################################
	## Version 1.1  // transmitted pwd is md5 encoded
	##################################################
	$checkpassword 		= $db->query("SELECT * FROM $tab[user] WHERE (user_login='$loginform[username]' OR user_name='$loginform[username]') AND user_password='$loginform[userpassword]'");


	/* 
	 * ##################################################
	 * ### VERSION 1.0 // trasmitted pwd was not encoded
	 * ##################################################
	 *  if($loginform[md5]) {
	 * 	 	$checkpassword = 	$db->query("SELECT * FROM $tab[user] WHERE (user_login='$loginform[username]' OR user_name='$loginform[username]') AND user_password='$loginform[userpassword]'");
	 * } else {
	 * 	if($checkuser[md5]) {
	 * 	 	$checkpassword = 	$db->query("SELECT * FROM $tab[user] WHERE (user_login='$loginform[username]' OR user_name='$loginform[username]') AND user_password='".md5($loginform[userpassword])."'");
	 * 	} else {
	 * 	 	$checkpassword = 	$db->query("SELECT * FROM $tab[user] WHERE (user_login='$loginform[username]' OR user_name='$loginform[username]') AND user_password=PASSWORD('$loginform[userpassword]')");
	 * 		if($checkuser) {	$db->query_str("UPDATE $tab[user] SET user_password='".md5($loginform[userpassword])."',md5='1' WHERE id='$checkuser[id]'");}
	 * 	}
	 * }
	 */

	 if(!$checkpassword) {
		$checkuser					= $db->query("SELECT * FROM $tab[user] WHERE user_login='$loginform[username]' OR user_name='$loginform[username]'");
		if(!$checkuser) 			{return 1;}
		if($checkuser[blocked]) 	{return 2;}
		if(!$checkuser[activated]) 	{return 4;}
	 	return 3;
	 } else $checkuser = $checkpassword;

	if($dothelogin) $queryplus = ", logins=logins+1";
	$UPDATE = $db->query_str("UPDATE $tab[user] SET
				last_activ='".time()."'$queryplus
				WHERE id='$checkuser[id]'");



	####################################################
	### Insert UserOptions
	####################################################
	if($tab[useroption]) {
		$tmp = $db->query_str("SELECT * FROM $tab[useroption] WHERE userid='$checkuser[id]'");
		while($option = $db->fetch_array($tmp)) {
			$checkuser["$option[name]"] = $option[value];
		}
	} else {
		saveerror("Update auf 1.5 durchführen.",__FILE__,__LINE__);
	}

	
	return $checkuser;
}
?>
