<?
####
if($dlsection=="pm" && is_array($sourceid)) {
	foreach($sourceid as $b) {
		$pm =	getpm($b);
		if($pm[toid]!=$login[id] && $pm[autid]!=$login[id]) {eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");}
		else {
			$user = getuser($pm[autid]);
			eval("\$pmdl = \"".gettemplate("pm.dl")."\";");
			$zip->addFile($pmdl,"PM von $user[user_name] - ".date("d-m-y",$pm[sendtime]).".txt");
		}
	}
	savetraffic(strlen($zip->file()));

	$dateiname = "PMs-Backup ".date("d-m-Y").".zip";

	header("Content-Type: application/octetstream");
	header("Content-Disposition: attachment; filename=".$dateiname);
	header("Pragma: no-cache");
	header("Expires: 0");

	echo $zip->file();
	exit;
}
#####
if($dlsection=="pm" && !is_array($sourceid)) {
	$pm =	getpm($sourceid);
	if($pm[toid]!=$login[id] && $pm[autid]!=$login[id]) {eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");}
	else {
		$user = getuser($pm[autid]);
		$dateiname = "PM-Backup ".date("m-Y").".zip";
		eval("\$pmdl = \"".gettemplate("pm.dl")."\";");
		$zip->addFile($pmdl,"PM von $user[user_name].txt");
		savetraffic(strlen($zip->file()));

    		header("Content-Type: application/octetstream");
	   	header("Content-Disposition: attachment; filename=".$dateiname);
		header("Pragma: no-cache");
		header("Expires: 0");

		echo $zip->file();
		exit;
	}
}
####
?>