<?
///////////////////////////////
// INCLUDES
///////////////////////////////
	require("./includes/inc.config.php");
	require("./includes/inc.functions.php");
	require("./includes/inc.vars.main.php");
	require("./includes/inc.logincheck.php");

//////////////////////////////
// CHECK IP banned
//////////////////////////////
	if($section != "admin") {
		$ohost = getenv("HTTP_HOST");
		$oip = getenv("REMOTE_ADDR");
		$ips = explode(" ",str_replace("\r\n"," ",$config[ban_ips]));
		foreach($ips as $ip) {
			if(!preg_match("#%#",$ip)) {
				if($ip==$oip) {
					$stopheaderoutput_main = TRUE;
					eval("\$inc[action] = \"".gettemplate("fail.ip.banned")."\";");
				}
			} else {
				$ip = str_replace(".","\.",$ip);
				$ip = str_replace("%","\d{1,3}",$ip);
				if(preg_match("#$ip#",$oip)) {
					$stopheaderoutput_main = TRUE;
					eval("\$inc[action] = \"".gettemplate("fail.ip.banned")."\";");
	}	}	}	}

//////////////////////////////
// ADMIN SECTION
//////////////////////////////
	if($section=="admin" && !$inc[action]) {
		$stopheaderoutput_main = TRUE;
		$stopheaderoutput_head = TRUE;
		if(!$login || !is_allowed("any")) {
			///////////////////////////
			// NO RIGHTS
			///////////////////////////
				$stopheaderoutput_main = FALSE;
				$stopheaderoutput_head = FALSE;
				eval("\$inc[action] = \"".gettemplate("fail.access.noaccess")."\";");
		} else {
			///////////////////////////
			//Load Admin
			///////////////////////////
				include("admin/index.php");
				exit;
		}

/////////////////////////////
// LOAD SECTION
/////////////////////////////
	} else {
		/////////////////////////////
		// Check Traffic Limit
		/////////////////////////////
		$maxtrafficreached = FALSE;
		if($config[limittraffic_activ]) {
			if($q=$db->query("SELECT * FROM $tab[config] WHERE name LIKE 'limit_%::".$sec[$section][id]."' OR name LIKE 'limit_%::0' LIMIT 1")) {
				preg_match("#limit_(\d+)::(\d+)::(\w+)::(\d+)#",$q[name], $re);
				$size 		= $re[1];
				$orgtime 	= $re[2];
				$timeb 		= $re[3];
				$closesectionid = $re[4];
				if($closesectionid == $sec[$section][id] || $closesectionid==0) {
					if($timeb=="day") 	{$time = mktime(0-$orgtime,0,0,date("n"),date("j"),date("y"));}
					if($timeb=="week") 	{$time = mktime(0-$orgtime*24*7,0,0,date("n"),date("j"),date("y"));}
					if($timeb=="month")	{$time = mktime(0-$orgtime*24*7*31,0,0,date("n"),date("j"),date("y"));}
					if($timeb=="year")	{$time = mktime(0-$orgtime*24*7*31*12,0,0,date("n"),date("j"),date("y"));}
					$q = $db->query_str("SELECT * FROM $tab[traffic] WHERE date>='$time' ORDER BY date DESC");
					while($result =$db->fetch_array($q)) {
						$traffic += $result[traffic];
						$date 	  = $result[date];
					}
					if($traffic > $size) {
						$maxtrafficreached = TRUE;
						if($timeb=="day")	$limitstopday=$date+($orgtime*3600*24);
						if($timeb=="week")	$limitstopday=$date+($orgtime*3600*24*7);
						if($timeb=="month")	$limitstopday=$date+($orgtime*3600*24*7*31);
						if($timeb=="year")	$limitstopday=$date+($orgtime*3600*24*7*31*12);
		}	}	}	}
		/////////////////////////////
		// LOAD MODULE
		/////////////////////////////
		if(!$inc[action]) {
			if($maxtrafficreached) {
				$limitstopday = mkdate($limitstopday,"d F Y");
				if($closesectionid==0) {
					$stopheaderoutput_main = TRUE;
					//$stopheaderoutput_head = TRUE;
					eval("\$inc[action] = \"".gettemplate("fail.trafficlimit.reached.all")."\";");
				} else {
					eval("\$inc[action] = \"".gettemplate("fail.trafficlimit.reached")."\";");
				}
			} else {
				if(!$loginfailed) {
					$lsec = $db->query("SELECT * FROM $tab[section] WHERE title='$section' LIMIT 1");
					if(!$lsec) 		{eval("\$inc[action] = \"".gettemplate("fail.section.nosection")."\";");}
					elseif(!$lsec[active])  {eval("\$inc[action] = \"".gettemplate("fail.section.inactiv")."\";");}
					else {
						$secpath = "sections/".$lsec[title]."/".$lsec[title].".php";
						if(!@is_file($secpath)) {
							eval("\$inc[action] = \"".gettemplate("fail.section.nofile")."\";");
						} else {
							ob_start();
							  //$oldlevel=error_reporting(0);
							    $consttimer->start("mainaction");
							      include($secpath);
							    $consttimer->stop("mainaction");
							  //error_reporting($oldlevel);
							$oboutput = ob_get_contents();
	}	}	}	}	}	}


///////////////////////////////
// GENERATE THE OUTLOOK
///////////////////////////////
	if(!$stopheaderoutput_main) {
		require("./includes/inc.vars.engine.php");
	}
	if(!$stopheaderoutput_head) {
		eval("\$inc[htmlhead] = \"".gettemplate("include.main.htmlhead")."\";");
		eval("\$inc[htmlfoot] = \"".gettemplate("include.main.htmlfoot")."\";");
	}

///////////////////////////////
// CONTENTS
///////////////////////////////
	if($oboutput) {$inc[action] = $oboutput.$inc[action];}

///////////////////////////////
// DBQUERY TIMER
///////////////////////////////
	unset($output);
	foreach($consttimer->timer as $timerid=>$timername) {
		if(preg_match("#dbaccess#",$timername)) {
			$dbaccess[] = $consttimer->gettime($timerid);
	}	}

///////////////////////////////
// STATS
///////////////////////////////
	$aver[dbaccess] = 	round(array_sum($dbaccess)/count($dbaccess),3);
	$aver[mainaction] = 	$consttimer->gettime(mainaction);
				$consttimer->stop("globalcreate");
	$construct_time = 	$consttimer->gettime("globalcreate",3);

	eval("\$inc[sworastats] = \"".gettemplate("include.main.sworastats")."\";");

///////////////////////////////
// AUSGABE ZUSAMMENSETZTEN	
///////////////////////////////
	unset($output);
	if(!$stopheaderoutput_head)	$output .= $inc[htmlhead]; 		   ## FIRST HTML TAGS ##
	if(!$stopheaderoutput_main)	$output .= $inc[maintable]; 	       ## OPENEING MENU TABLE ##
	if(!$stopheaderoutput_action)	$output .= $inc[action]; 	  	        ##  MAIN PAGE ##
	if(!$stopheaderoutput_main)	$output .= $inc[maintableclose];  	## CLOSING MENU TABLE ##
	if(!$stopheaderoutput_main)	$output .= $inc[sworastats]; 	  		     ## STATS ##
	if(!$stopheaderoutput_head)	$output .= $inc[htmlfoot];	  	    ## LAST HTML TAGS ##


///////////////////////////////
// Traffic
///////////////////////////////
	savetraffic($traffic=strlen($output)+4);
	$thetraffic=$traffic/1024;
	if(!$stopheaderoutput_head && !$stopheaderoutput_action) 
	{
		$traffictext = "<!-- ".round($thetraffic,4)."Kb //-->\r\n";
		$output = preg_replace("#^(<!.*>)#","\\1\r\n$traffictext",$output);
		//$output = ("<!-- ".round($thetraffic,4)."Kb //-->\r\n") . $output;
	}
	
///////////////////////////////
// DISCONNECT FROM SQL
///////////////////////////////
	$db->disconnect();

//////////////////////////////
// Ausgabe
//////////////////////////////
	style($output);
	flush();
	exit;
?>
