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

class dbaccess {

	var $db_server_id = 0;
	var $query_id = "";

	var $errordesc = "";
	var $errorcode = "";

	var $db_server = "";
	var $db_user = "";
	var $db_password = "";
	var $db_db = "";

	var $count = 0;

	var $appname = "Swora.Sql.DB";

	###############
	function erroroutput($msg="") {
    		global $adminmail;

    		$this->errordesc = 	mysql_error();
    		$this->errorcode =	mysql_errno();

    		$message ="Database error in $this->appname: $msg\n";
    		$message.="mysql error:<b> $this->errordesc\n</b>";
    		$message.="mysql error number: $this->errorcode\n";
    		$message.="Date: ".date("d.m.Y @ H:i")."\n";
    		$message.="Script: ".getenv("REQUEST_URI")."\n";
    		$message.="Referer: ".getenv("HTTP_REFERER")."\n<hr width=20%>";

		saveerror($message,__FILE__,__LINE__,0,0);

    		$message.="Administrator: <a href=\"mailto:$adminmail\">$adminmail</a>\n";

		eval("\$message = \"".gettemplate("error",0)."\";");
		die($message);
		exit;
	}
	###############
	function connect() {
		if( !$this->db_server_id ) {
			$this->db_server_id = @mysql_pconnect($this->db_server,$this->db_user,$this->db_password);

			if(!$this->db_server_id) {
				$this->erroroutput("No Connection to DataBase");
			} else {
				if($this->db_db!="") {
					$this->select_db();
				}
			}
		}
	}
	###############
	function disconnect() {
		if($this->db_server_id) {
			mysql_close($this->db_server_id);
		}
	}
	###############
  	function select_db() {
		if($this->db_db!="") {
			if(!@mysql_select_db($this->db_db, $this->db_server_id)) {
				$this->erroroutput("Cannot user database ".$this->db_db);
			}
		}
  	}
	###############
	function fetch_array($query="0") {
		if(!$query) {
			$query_id = $this->query_id;
		}
		$this->record = mysql_fetch_array($query);
		if(is_array($this->record)) {
			foreach($this->record as $a=>$b) {
		 		$this->record[$a] = stripslashes($b);
		 	}
		 
		} 
		return $this->record;
	}
	###############
	function free_result($query_id=0) {
		if($query_str) {
			$this->query_id = $query_id;
		}

		return @mysql_free_result($this->query_id);
	}
	###############
	function insert_id() {
		return @mysql_insert_id($this->db_server_id);
	}
	###############
	// --- Puplic used function --- //
	###############
	function fetch_row($query="0") {
		if(!$query) {
			$query_id = $this->query_id;
		}
		$this->record = mysql_fetch_row($query);
		if(is_array($this->record)) {
			foreach($this->record as $a=>$b) {
		 		$this->record[$a] = stripslashes($b);
		 	}
		 
		}
		return $this->record;
	}
	###############
	function field_name($query="0",$id) {
		if(!$query) {
			$query_id = $this->query_id;
		}
		$this->record = mysql_field_name($query,$id);
		return $this->record;
	}
	###############
	function num_fields($query="0") {
		if(!$query) {
			$query_id = $this->query_id;
		}
		$this->record = mysql_num_fields($query);
		return $this->record;
	}
	###############
	function query($query_str,$printfail=1) {
		$query =	$this->query_str($query_str,$printfail);
		$erg = 		$this->fetch_array($query);
				$this->free_result($query);
		$query = "";
		return $erg;
	}
	###############
	function query_str($query_str,$printfail=1) {
		///////////////
		// TIMER START
		///////////////
		global $consttimer;
		if(is_object($consttimer)) {
			$consttimer->start("dbaccess:".$this->count);
		}
		///////////////
		// DB QUERY
		///////////////
		$query = @mysql_query($query_str, $this->db_server_id);
		///////////////
		// TIMER STOP
		///////////////
		if(is_object($consttimer)) {
			$consttimer->stop("dbaccess:".$this->count);
		}
		$this->count++;

		if(!$query && $printfail) {$this->erroroutput("Query failed => $query_str");}
		return $query;
	}
	###############
}

$db 		= new DbAccess;
?>
