<?
//////////////////////////////////
//	(C)opyright by XeRoc	//
//////////////////////////////////
//   Skript written for Swora	//
// http://swora.berlios.de      //
//////////////////////////////////
//	all rights reserved	//
//////////////////////////////////

define ("CRLF","\n");

class sendmail {

	var $appname = 		"Mailingsystem";
	var $use_smtp = 	"0";	// PHP 4.2

	var $mail_target = 	"";
	var $mail_subject = 	"";
	var $mail_body = 	"";
	var $mail_header = 	"";

	var $mime_file = 	"";
	var $boundary =		"";

	///////////////////////////////////////
	// FUNCTIONS
	///////////////////////////////////////
	######################
	function failedsending($msg=0) {   		
    		global $adminmail;
		$this->reset();

		saveerror("Fail on Mailing","class.mail.php",__LINE__,0);

		if(!$msg) $msg = "Die Email konnte nicht ordnungsgemäß abgeschickt werden.";
    		$message="Mailing error in $this->appname: $msg\n<br>";
    		$message.="<b></b><br>";
    		$message.="Date: ".date("d.m.Y @ H:i")."\n<br>";
    		$message.="Script: ".getenv("REQUEST_URI")."\n<br>";
    		$message.="Referer: ".getenv("HTTP_REFERER")."\n<br><hr width=20%>";
    		$message.="Administrator: <a href=\"mailto:$adminmail\">$adminmail</a>\n<br><br>";

		eval("\$message = \"".gettemplate("error")."\";");
		die($message);
		exit;
	}
	######################
	function prepareheader($str="") {
		global $httphost;
		if($str) {
			$headerexplode = explode("\n",$str);
			foreach($headerexplode as $headerpart) {
				$headerarray[] = str_replace("\r","",trim($headerpart));
			}
			$header = trim(join(CRLF, $headerarray));
			if(!preg_match("#from:(.*)#i",$header)) {
				$header .= CRLF."FROM: mail@$httphost";
			}
		}
		return $header;
	}
	######################
	function addlastline($what=0) {
		eval("\$mailend = \"".gettemplate("mailend")."\";");
		return ($what.$mailend);
	}
	######################
	function reset() {
		$this->mail_target = 	"";
		$this->mail_subject = 	"";
		$this->mail_body = 	"";
		$this->mail_header = 	"";
	}
	######################
	function domailing($failed=1) {
		global $smtp,$config;
		if(!$this->use_smtp) {
			$mailed = @mail($this->mail_target,$this->mail_subject,$this->mail_body,$this->mail_header);
		} else {
			$smtp->connect($config[smtp_host],$config[smtp_user],$config[smtp_pass]);
			$mailed = $smtp->mail($this->mail_target,$this->mail_subject,$this->mail_body,$this->mail_header);
		}

		if(!$mailed) {
			saveerror("Mail not sent -- $this->mail_target -- \r\n -- $this->mail_subject -- \r\n -- $this->mail_body -- \r\n",__FILE__,__LINE__,0,1);
			if($failed) $this->failedsending();
		}
		$this->reset();
	}
	######################
	function mail($target="",$subject,$body,$header,$failer=1) {
		global $config;
		$this->mail_target  = 	$target;
		$this->mail_subject = 	$subject;
		$this->mail_body    = 	$this->addlastline($body);
		$header 	    = 	$this->prepareheader($header);
		$this->header       = 	trim($header);
		$this->use_smtp	    =   0;
		$this->domailing($failer);
	}
	######################
	function mail_mime($target,$subject,$body,$header) {
		$header = $this->prepareheader($header);

		if(!$this->boundary) $this->boundary = strtoupper(md5(uniqid(time())));
		$nparts = sizeof($this->mimeparts);

		$this->mail_target  = 	$target;
		$this->mail_subject = 	$subject;

		$this->mail_header  = 	"MIME-Version: 1.0".CRLF;
		$this->mail_header .= 	"Content-Type: multipart/mixed; charset=\"iso-8859-1\";".CRLF."\tboundary=\"$this->boundary\"".CRLF;
		//$this->mail_header .=	"Content-Transfer-Encoding: BASE64".CRLF;
		$this->mail_header .= 	trim($header).CRLF;

		$this->mail_body  = 	"--$this->boundary".CRLF;
		$this->mail_body .= 	"Content-Type: text/plain".CRLF;
		$this->mail_body .= 	"Content-Transfer-Encoding: 8bit".CRLF.CRLF;
		$this->mail_body .= 	$this->addlastline($body).CRLF;
		//$this->mail_body .= 	"--".$this->boundary.CRLF.CRLF;

		foreach($this->mimeparts as $part) {
			$this->mail_body .= "--".$this->boundary.CRLF.$part.CRLF;
		}
		$this->mail_body .= 	"--".$this->boundary.CRLF;

		$this->domailing();
	}
	######################
	function addfile($data,$filename) {
		if (empty($data)) return 0;
		$data = chunk_split(base64_encode($data));

		$this->mimeparts[] = 	 "Content-Type: application/x-zip-compressed; name=\"".$filename."\"".CRLF
					."Content-Transfer-Encoding: base64".CRLF
					."Content-Disposition: attachment; filename=\"".$filename."\"".CRLF.CRLF
					.$data;
	}
	######################
}

$sendmail 	= new SendMail;
?>
