<?
///////////////////////////////////
// SOME PHP VARS
///////////////////////////////////
	$php_version_array 	= explode(".",phpversion());
	$php_version 		= (double) ($php_version_array[0] + ($php_version_array[1]/10));

	if($php_version>=4.2) {
		if(is_array($_GET)) 	{foreach($_GET as $a=>$b) 	{$GLOBALS[$a] = $b;}}
		if(is_array($_POST)) 	{foreach($_POST as $a=>$b) 	{$GLOBALS[$a] = $b;}}
		if(is_array($_COOKIE)) 	{foreach($_COOKIE as $a=>$b) 	{$GLOBALS[$a] = $b;}}
		if(is_array($_SERVER)) 	{foreach($_SERVER as $a=>$b) 	{$GLOBALS[$a] = $b;}}
		if(is_array($_ENV)) 	{foreach($_ENV as $a=>$b) 	{$GLOBALS[$a] = $b;}}
	} else {
	/*
	 *	if($HTTP_POST_FILES) 	{foreach($HTTP_POST_FILES as $a=>$b) 	{$GLOBALS[$a] = $b;}}
	 *	if($HTTP_POST_VARS) 	{foreach($HTTP_POST_VARS as $a=>$b) 	{$GLOBALS[$a] = $b;}}
	 *	if($HTTP_COOKIE_VARS) 	{foreach($HTTP_COOKIE_VARS as $a=>$b) 	{$GLOBALS[$a] = $b;}}
	 *	if($HTTP_GET_VARS) 	{foreach($HTTP_GET_VARS as $a=>$b)	{$GLOBALS[$a] = $b;}}
	 *	if($HTTP_ENV_VARS) 	{foreach($HTTP_ENV_VARS as $a=>$b) 	{$GLOBALS[$a] = $b;}}
	 *	if($HTTP_SERVER_VARS) 	{foreach($HTTP_SERVER_VARS as $a=>$b) 	{$GLOBALS[$a] = $b;}}
	 */	 
	}
	unset($inc);

///////////////////////////////////
// DEFAULT TEMPLATEFOLDER
///////////////////////////////////
	$style[templatefolder] 		= "templates/default";


///////////////////////////////////
// BETTER THE POSTET FORM
//////////////////////////////////(
	set_magic_quotes_runtime(0);
	if(is_array($form)) { //  && !get_magic_quotes_gpc()
		foreach($form as $a => $b) {
			$form["$a"] = addslashes(trim($b));
	}	}

///////////////////////////////////
// SOME SESSIONS AND VARS
///////////////////////////////////
	error_reporting  (E_ERROR | E_WARNING | E_PARSE);
	session_name( swid );
	session_start();
	$SID 		= session_id();
	$session 	= "swid=".session_id();
	$session_form 	= "<input type=hidden name=\"swid\" value=\"".session_id()."\">";
	$relativpath 	= $DOCUMENT_ROOT."/";
	$tmp		= parseurl($httpurl,0);
	$httphost 	= $tmp[host];unset($tmp);

///////////////////////////////////
// Classes
///////////////////////////////////
	$dir = dir("./classes");
	while($f=$dir->read()) {
		if(preg_match("#^class\.#",$f)) {
			include("./classes/".$f);
		}
	}

///////////////////////////////////
// DECLARATIONS
///////////////////////////////////
	if(!$database) // NOT INSTALLED
		{header("LOCATION: install.php");exit;}
	$db->db_server 		= $database[host];
	$db->db_user 		= $database[user];
	$db->db_password 	= $database[pass];
	$db->db_db 		= $database[db];
	$db->connect();unset($database);

///////////////////////////////////
// SESSION VARS / CONFIG-ARRAY
///////////////////////////////////
	$config 	= getconfig();
	$tab 		= gettabs();
	$points 	= getpoints();
	$section 	= strtolower($section);
	$sec 		= getsection("all");
	if(!$section) {
		$section = $sec[$config[startsection]];
		$section = $section[title];
	}
	if(!$section) $section=news;
	$counttemplateloads = 0;

///////////////////////////////////
// GET CONSTRUCTION TIME START
///////////////////////////////////
	$consttimer->start("globalcreate");

///////////////////////////////////
// STYLE FETCH
///////////////////////////////////
	if($section=="admin"){$styleset = "default";}
	else {
		if($config[defaultstyleset] && $config[stylesetstatic]) {
			$styleset = $config[defaultstyleset];
			if(is_dir("templates/$styleset")) {setcookie("styleset_c",$styleset,time()+60*24*356);}
			else 				  {$styleset = "default";}
		} else {
			if($HTTP_GET_VARS["stylereset"]) {@setcookie("styleset_c","",-1);$HTTP_COOKIE_VARS["styleset_c"]="";}
			if(!$HTTP_COOKIE_VARS["styleset_c"] && !$HTTP_POST_VARS["styleset"]) {
				if(!$styleset=$config[defaultstyleset]) $styleset = "default";
			}
			elseif($HTTP_POST_VARS["styleset"] && $HTTP_COOKIE_VARS["styleset_c"]) {
				$styleset = $HTTP_POST_VARS["styleset"];
				setcookie("styleset_c",$HTTP_POST_VARS["styleset"],time()+3600*24*30);
			}
			elseif(!$HTTP_POST_VARS["styleset"] && $HTTP_COOKIE_VARS["styleset_c"]) {
				$styleset = $HTTP_COOKIE_VARS["styleset_c"];
			}
			else {if(!$styleset=$config[defaultstyleset]) $styleset = "default";}

			if(@is_dir("templates/$styleset")) {setcookie("styleset_c",$styleset,time()+60*24*356);}
			else 				  {$styleset = "default";}
	}	}

///////////////////////////////////
// USER IDENDITY
///////////////////////////////////
	if(!$HTTP_COOKIE_VARS[cid]) {
		$cid = generatepass(25);
		@setcookie("cid",$cid,time()+(3600*365));
	} else {
		$cid = $HTTP_COOKIE_VARS[cid];
		@setcookie("cid",$cid,time()+(3600*365));
	}

///////////////////////////////////
// FOR THE index.php
///////////////////////////////////
	if(!preg_match("/index.php/",$REQUEST_URI)) {header("LOCATION: index.php");exit;}

///////////////////////////////////
// STYLE SELECT
///////////////////////////////////
	$style[templates] 		= "templates";
	$style[styleset] 		= $styleset;
	$style[templatefolder] 		= "$style[templates]/$styleset";
	$style[cssfile] 		= "$section/$section.css";

	$dir = dir("./".$style[templates]."/default");
	while($f = $dir->read()) {
		if(preg_match("#\.css$#iUs",$f)) {
			if(@is_file("./".$style[templatefolder]."/".$f))
				$styleseets 	.= "\t\t<link rel=\"stylesheet\" href=\"$httpurl/$style[templatefolder]/$f\">\r\n";
			else
				$styleseets 	.= "\t\t<link rel=\"stylesheet\" href=\"$httpurl/$style[templates]/default/$f\">\r\n";
		}
	}
	
	if($section!="admin") {
		if(@is_file("$style[templatefolder]/$style[cssfile]")) {
			$styleseets 	.=  "\t\t<link rel=\"stylesheet\" href=\"$httpurl/$style[templatefolder]/$style[cssfile]\">\r\n";
		}
		elseif(@is_file("$style[templates]/default/$style[cssfile]"))
			$styleseets 	.=  "\t\t<link rel=\"stylesheet\" href=\"$httpurl/$style[templates]/default/$style[cssfile]\">\r\n";
	}

///////////////////////////////////
// LOGIN FORM TEMPLATE
///////////////////////////////////
	eval("\$inc[loginform] = \"".gettemplate("loginform")."\";");


///////////////////////////////////
// SOME HEADERS
///////////////////////////////////
	//header("Cache-Control: private, pre-check=0, post-check=0, max-age=0");
	//header("Expires: ".gmdate("D, d M Y H:i:s", time())." GMT");
	//header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");

///////////////////////////////////
// INCLUDE OTHER FILES INTO MAIN.PHP
///////////////////////////////////
$dir = dir("./includes");
while($f=$dir->read()) {
	if(preg_match("#^m_#",$f))
		include("./includes/".$f);
}
?>
