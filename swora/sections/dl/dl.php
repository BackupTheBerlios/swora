<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");
#################
if($dlsection) {
	$path = "./sections/dl/";
	if(is_file($path."dl_".$dlsection.".php")) {
		@include($path."dl_".$dlsection.".php");
	} else {eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");}
} else {
	header("LOCATION: index.php");
}
?>