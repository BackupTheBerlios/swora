<?php
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
// THANX TO Jeroen Maathuis FOR
// THIS ZIP-GEN-SOFTWARE
// EDITED BY XeRoc
//////////////////////////////////

class zipfile  
{  
    var $datasec      = array();
    var $ctrl_dir     = array();
    var $eof_ctrl_dir = "\x50\x4b\x05\x06\x00\x00\x00\x00";
    var $old_offset   = 0;

///////////////////////////////////////////
// FAILER
///////////////////////////////////////////

	function nozlib($msg="") {    		
    		global $adminmail,$PHP_SELF;

		saveerror("No zLib","class.zip.php",__LINE__,0);

    		$message="Error: Ein notwendiges Modul konnte nicht gefunden werden: <b>zlib</b><br>";
    		$message.="Date: ".date("d.m.Y @ H:i")."\n<br>";
    		$message.="Script: ".getenv("REQUEST_URI")."\n<br>";
    		$message.="Referer: ".getenv("HTTP_REFERER")."\n<br><hr width=20%>";
    		$message.="Administrator: <a href=\"mailto:$adminmail\">$adminmail</a>\n<br><br>";


    		$message.="Administrator: <a href=\"mailto:$adminmail\">$adminmail</a>\n";
		eval("\$message = \"".gettemplate("error")."\";");
		die($message);
		exit;
	}

///////////////////////////////////////////
// ADD FILE
///////////////////////////////////////////

	function addFile($data, $name) {
		if(!extension_loaded("zlib")) {
			$this->nozlib();
		} else {
       			$name = str_replace('\\', '/', $name);
		        $fr   = "\x50\x4b\x03\x04"; 
	        	$fr   .= "\x14\x00";            // ver needed to extract 
		        $fr   .= "\x00\x00";            // gen purpose bit flag 
        		$fr   .= "\x08\x00";            // compression method 
		        $fr   .= "\x00\x00\x00\x00";    // last mod time and date 

	        	$unc_len = strlen($data);
		        $crc     = crc32($data);

		      	$zdata   = @gzcompress($data);
		        $zdata   = substr(substr($zdata, 0, strlen($zdata) - 4), 2); // fix crc bug
		        $c_len   = strlen($zdata);
	        	$fr      .= pack('V', $crc);             // crc32
		        $fr      .= pack('V', $c_len);           // compressed filesize
	        	$fr      .= pack('V', $unc_len);         // uncompressed filesize
		        $fr      .= pack('v', strlen($name));    // length of filename
	        	$fr      .= pack('v', 0);                // extra field length
		        $fr      .= $name;

	        	$fr .= $zdata;
		        $fr .= pack('V', $crc);                 // crc32
	        	$fr .= pack('V', $c_len);               // compressed filesize
		        $fr .= pack('V', $unc_len);             // uncompressed filesize

	        	$this -> datasec[] = $fr;
		        $new_offset        = strlen(implode('', $this->datasec));

	        	$cdrec = "\x50\x4b\x01\x02";
		        $cdrec .= "\x00\x00";                // version made by
	        	$cdrec .= "\x14\x00";                // version needed to extract
		        $cdrec .= "\x00\x00";                // gen purpose bit flag
	        	$cdrec .= "\x08\x00";                // compression method
		        $cdrec .= "\x00\x00\x00\x00";        // last mod time & date
        		$cdrec .= pack('V', $crc);           // crc32
		        $cdrec .= pack('V', $c_len);         // compressed filesize
        		$cdrec .= pack('V', $unc_len);       // uncompressed filesize
		        $cdrec .= pack('v', strlen($name) ); // length of filename
        		$cdrec .= pack('v', 0 );             // extra field length
		        $cdrec .= pack('v', 0 );             // file comment length
        		$cdrec .= pack('v', 0 );             // disk number start
		      	$cdrec .= pack('v', 0 );             // internal file attributes
	        	$cdrec .= pack('V', 32 );            // external file attributes - 'archive' bit set

		        $cdrec .= pack('V', $this -> old_offset ); // relative offset of local header
		        $this -> old_offset = $new_offset;

		        $cdrec .= $name;

		        $this -> ctrl_dir[] = $cdrec;
		}
	}

///////////////////////////////////////////
// OUTPUT
///////////////////////////////////////////

	function file() {
		if(!@extension_loaded("zlib")) {
			$this->nozlib();
		} else {
		        $data    = implode('', $this -> datasec);
       		 	$ctrldir = implode('', $this -> ctrl_dir);

		        return
        		    $data .
	        	    $ctrldir .
	        	    $this -> eof_ctrl_dir .
		            pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries "on this disk"
        		    pack('v', sizeof($this -> ctrl_dir)) .  // total # of entries overall
		            pack('V', strlen($ctrldir)) .           // size of central dir
        		    pack('V', strlen($data)) .              // offset to start of central dir
	        	    "\x00\x00";                             // .zip file comment length
		}
	}

}
$zip 		= new ZipFile;
?>
