<?
//////////////////////////////////
//	(C)opyright by XeRoc	    //
//	ICQ: 85970079		        //
//////////////////////////////////
//   Skript written for Swora	//
// http://swora.berlios.de      //
//////////////////////////////////
//	all rights reserved	        //
//////////////////////////////////


//////////////////////////////////
// THIS SOFTWARE IS IN DEVELOPMENT
// DONNOT USE IT
//////////////////////////////////

class SMTPMailer {
	//////////////////////
	// VARS
	//////////////////////
		var $appname = "SMTP-Client";
		var $socket;
		var $errno;
		var $errdesc;

		var $CommingFrom; // default Wert
		var $smtp_host;
		var $smtp_user;
		var $smtp_pass;

	//////////////////////
	// On Errors
	//////////////////////
		function erroroutput($msg="") {
    			global $adminmail;

			if($this->socket) {$this->disconnect();}

    			$message="Smtp Error in $this->appname: $msg\n<br>";
	    		$message.="SMTP error:<b> $this->errdesc\n</b><br>";
    			$message.="SMTP error number: $this->errno\n<br>";
    			$message.="Date: ".date("d.m.Y @ H:i")."\n<br>";
	    		$message.="Script: ".getenv("REQUEST_URI")."\n<br>";
	    		$message.="Referer: ".getenv("HTTP_REFERER")."\n<br><hr width=20%>";

			saveerror($message,__FILE__,__LINE__,0,1);
	    		$message.="Administrator: <a href=\"mailto:$adminmail\">$adminmail</a>\n<br><br>";

			eval("\$message = \"".gettemplate("error")."\";");
			die($message);
			exit;
		}
	//////////////////////
	//
	//////////////////////
		function SMTPMailer() {
			global $httphost;
			$this->CommingFrom = "Wemaster@$httphost";
			$this->reset();
		}
		function reset() {
			return 1;
		}
		function emptycache() {
			while(!feof($this->socket)) $str .= fgets($this->socket,1);
			return $str;
		}


	//////////////////////
	// CONNECT
	//////////////////////
		function connect($host=0,$user=0,$pwd=0) {
			$this->socket = @pfsockopen($host, 25, $this->errno, $this->errdesc, 10);
			if(!$this->socket) {$this->erroroutput("No Connection To SMTP-Server");}
			sleep(3); // GIBE TIME TO RESPONSE
			if(substr($this->get(1024,0),0,3)!="220") {$this->erroroutput("SMTP not Availible");}

			$this->put("EHLO $host\r\n");
			echo $this->get();

			//$str = $this->emptycache();

				/* $this->get(250);
				 * $this->get(250);
				 * $this->get(250);
				 * $this->get(250);
				 * $this->get(250);
 				 */

				/*
				 * 	250-localhost
				 *	250-8BITMIME
				 *	250-AUTH=LOGIN
				 *	250-AUTH LOGIN
				 *	250 HELP
				 */

			if($pwd && $user) {
				$this->put("AUTH LOGIN\r\n");
				$this->checkget(334);
				$this->put(base64_encode($user) . "\r\n");
				$this->checkget(334);
				$this->put(base64_encode($pwd) . "\r\n");
				$this->checkget(235);
			}
		}
		
	//////////////////////
	// DISCONNECT
	//////////////////////
		function disconnect() {
			$this->put("QUIT\r\n");
			@fclose($this->socket);
			$this->socket = "";
		}

	/////////////////////
	// Get
	/////////////////////
		function get($length=256,$fail=1) { 
			$str = fgets($this->socket,$length);
			if(!$str && $fail) {$this->erroroutput("Nothing returns from SMTP");}
			else {return $str;}
		}

	/////////////////////
	// Check Return
	/////////////////////
		function checkget($code) {
			$str = $this->get();
			if(substr($str,0,3)!=$code) {
				$this->errdesc = trim($str);
				$this->erroroutput("Failed to send a Mail via SMTP");
			}
		}

	/////////////////////
	// PUT
	/////////////////////
		function put($str) { 
			$re = fputs($this->socket,$str);
			if(!$re) {
				$this->erroroutput("Failed to send to SMTP");
			} else {
				return $re;
			}
		}

	/////////////////////
	// Fix Header
	/////////////////////
		function fix_header($str) {
			$header_array = explode("\r\n",trim($str));
			foreach($header_array as $val) {
				list($name,$value) = explode(":",trim($val));
				$name=trim($name);$value=trim($value);
				if(strtolower($name) != "to") {
					$headers .= $name.": ".$value."\r\n";
				}
				if(strtolower($name)=="from") {
					$this->CommingFrom = $value;
				}
			}
			return $headers;
		}

	/////////////////////
	// Mail
	/////////////////////
		function mail($mail=0,$subject=0,$text=0,$headers="") {
			if(!$mail) {
				erroroutput("No Valid Email");
			} else {
				$extra_headers = $this->fix_header($headers);

				$this->put("MAIL FROM: <$this->CommingFrom>\r\n");
				$this->checkget(250);
				$this->put("RCPT TO: <$mail>\r\n");
				$this->checkget(250);
				$this->put("DATA\r\n");
				$this->checkget(354);

				/////////////////////////
				$this->put("SUBJECT: $subject\r\n");
				$this->put("TO: $mail\r\n");
				$this->put("$extra_headers");

				$this->put("\r\n");
				$this->put("$text\r\n");
				/////////////////////////
				$this->put("\r\n.\r\n");
				$this->checkget(250);

				$this->reset();
				return 1;
			}
		}
	/////////////////////
}

$smtp 		= new SMTPMailer;
?>
