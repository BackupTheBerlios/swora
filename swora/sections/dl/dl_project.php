<?
$project = getproject($sourceid);
if(($project[userid]!=$login[id]) && !is_freesource("project",$project[id],"3")) {
	eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
} else {
	$filesq = $db->query_str("SELECT * FROM $tab[code_code] WHERE projectid='$sourceid'");
	while($file = $db->fetch_array($filesq)) {
		$zip->addFile($file[code],$file[filename]);
	}
	$dateiname = $project[title].".zip";

	savetraffic(strlen($zip->file()));

	header("Content-Type: application/octetstream");
   	header("Content-Disposition: attachment; filename=".$dateiname);
	header("Pragma: no-cache");
	header("Expires: 0");

	echo $zip->file();
	exit;
}
?>