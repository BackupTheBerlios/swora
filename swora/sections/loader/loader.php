<?
if(!preg_match("/index.php/",$REQUEST_URI)) die("Direct Open not allowed.");

///////////////////
// LOAD
///////////////////

if($cat) {
	$load = $db->query("SELECT * FROM $tab[loader] WHERE title='$cat'");
	if(!$load) {
		saveerror("No Cat @ Loader: $cat",__FILE__,__LINE__,0,1);
		eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
	} else {
		$db->query_str("UPDATE $tab[loader] SET loads=loads+1 WHERE id='$load[id]'");
		//if(($load[file][0]=="/")) {$load[file] = substr($load[file],1,strlen($load[file]));}

		if($load[mode]==1) {		// Weiterleitung
			header("LOCATION: $load[file]");



		} elseif($load[mode]==2) {	// lokale Datei Anhängen
			if(is_file($load[file])) {
				$inc[action] = implode("",file($load[file]));
			} else {
				saveerror("Loader: Link ungültig",__FILE__,__LINE__);
				eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
			}



		} elseif($load[mode]==3) {		// URL
			if($fp = fopen($load[file],"r")){
				while(feof($fp)==0) {$inc[action] .= fgets($fp,1024);}
				fclose($fp);
			} else {
				saveerror("Loader: Link ungültig",__FILE__,__LINE__);
				eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
			}
		}
	}
} else {
	eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
}
///////////////////

?>
