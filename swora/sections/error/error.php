<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

////////////////
// ERROR CODES
////////////////
if($error) {
	//saveerror("HTTP# $error @ $HTTP_REFERER",__FILE__,__LINE__,0,1);
	eval("\$inc[action] = \"".gettemplate($error)."\";");
}

?>