<?
if($fid) {
	$db->query_str("UPDATE $tab[ucscripts] SET dls=dls+1 WHERE id='$fid'");
	$file = get_ucscripts($fid);
	savetraffic(@filesize($file[path]));

	header("Content-Type: application/octetstream");
   	header("Content-Disposition: attachment; filename=".@basename($file[path]));
	header("Pragma: no-cache");
	header("Expires: 0");

	$fp = @fopen($file[path],"rb");
		$str = @fread($fp,@filesize($file[path]));
	@fclose($fp);
	die($str);
	exit;
}
?>