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


class FtpAccess {
	////////////////////////////////////////
	// VARS
	////////////////////////////////////////
		var $socket;
		var $mode = FTP_BINARY;

		var $ftp_host;
		var $ftp_port;
		var $ftp_user;
		var $ftp_pwd;

	////////////////////////////////////////
	// ERROR-HANDLER
	////////////////////////////////////////
		function erroroutput($msg="") {
			if($this->socket) {
				$this->disconnect();
			}

    			$message="ERROR: <b>$msg</b>\n<br>";
    			$message.="Date: ".date("d.m.Y @ H:i")."\n<br>";

	    		$message.="Administrator: <a href=\"mailto:$adminmail\">$adminmail</a>\n";
			eval("\$message = \"".gettemplate("error")."\";");
			die($message);
			exit;
		}

	////////////////////////////////////////
	// KONSTRUKTOR
	////////////////////////////////////////
		function FtpAccess() {
			$this->ftp_host = "";
			$this->ftp_port = "";
			$this->ftp_user = "";
			$this->ftp_pwd  = "";
		}

	////////////////////////////////////////
	// CONNECT
	////////////////////////////////////////
		function connect($host,$port=21,$user,$pwd) {
			$this->ftp_host	= $host;
			$this->ftp_port	= $port;
			$this->ftp_user = $user;
			$this->ftp_pwd 	= $pwd;
			if($this->socket=@ftp_connect($this->ftp_host,$this->ftp_port)) {
				return $this->auth();
			} else {
				return 0;
			}
		}

	////////////////////////////////////////
	// DISCONNECT
	////////////////////////////////////////
		function disconnect() {
			if($this->socket) {
				@ftp_quit($this->socket);
				$this->FtpAccess();
			}
		}

	////////////////////////////////////////
	// AUTH
	////////////////////////////////////////
		function auth() {
			return @ftp_login($this->socket,$this->ftp_user,$this->ftp_pwd);
		}

	///////////////////////////////////////
	// SET MODE
	///////////////////////////////////////
		function setmode($mode=1) {
			switch($mode) {
				case 1:
					$this->mode = FTP_ASCII;
					break;
				case 2:
					$this->mode = FTP_BINARY;
					break;
				default:
					$this->mode = FTP_BINARY;
					break;
			}
		}

	////////////////////////////////////////
	// PRINT WORKING DIRECTORY
	////////////////////////////////////////
		function cpwd() {
			return @ftp_pwd($this->socket);
		}

	////////////////////////////////////////
	// CDUP
	////////////////////////////////////////
		function ccdup() {
			return @ftp_cdup($this->socket);
		}

	////////////////////////////////////////
	// CHDIR
	////////////////////////////////////////
		function cchdir($str) {
			return @ftp_chdir($this->socket,$str);
		}

	////////////////////////////////////////
	// MKDIR
	////////////////////////////////////////
		function cmkdir($str) {
			return @ftp_mkdir($this->socket,$str);
		}

	////////////////////////////////////////
	// RMDIR
	////////////////////////////////////////
		function crmdir($str) {
			return @ftp_rmdir($this->socket,$str);
		}

	////////////////////////////////////////
	// NLIST
	////////////////////////////////////////
		function cnlist($str) {
			return @ftp_nlist($this->socket,$str);
		}

	////////////////////////////////////////
	// RAWLIST
	////////////////////////////////////////
		function crawlist($str) {
			return ftp_rawlist($this->socket,$str);
		}

	///////////////////////////////////////
	// PASSIVER MODUS
	////////////////////////////////////////
		function cpasv($int) {
			return @ftp_pasv($this->socket,$int);
		}

	///////////////////////////////////////
	// GET
	////////////////////////////////////////
		function cget($rfile,$lfile) {
			return @ftp_get($this->socket,$lfile,$rfile,$this->mode);
		}

	///////////////////////////////////////
	// FILE GET
	////////////////////////////////////////
		function cfget($rfile,$fp) {
			return @ftp_fget($this->socket,$fp,$rfile,$this->mode);
		}

	///////////////////////////////////////
	// PUT
	////////////////////////////////////////
		function cput($lfile,$rfile) {
			return @ftp_put($this->socket,$lfile,$rfile,$this->mode);
		}

	///////////////////////////////////////
	// FILE PUT
	////////////////////////////////////////
		function cfput($fp,$rfile) {
			return @ftp_fput($this->socket,$rfile,$fp,$this->mode);
		}

	///////////////////////////////////////
	// SIZE
	////////////////////////////////////////
		function csize($str) {
			return @ftp_size($this->socket,$str);
			// returns -1 on failer or directory
		}

	///////////////////////////////////////
	// RENAME
	////////////////////////////////////////
		function crename($name1,$name2) {
			return @ftp_rename($this->socket,$name1,$name2);
		}

	///////////////////////////////////////
	// DELETE
	////////////////////////////////////////
		function cdelete($str) {
			return @ftp_delete($this->socket,$str);
		}

	////////////////////////////////////////
}
$ftp 		= new FtpAccess;
?>
