<?
//////////////////////////////////
//	(C)opyright by XeRoc	//
//////////////////////////////////
//   Skript written for Swora	//
// http://swora.berlios.de      //
//////////////////////////////////
//	all rights reserved	//
//////////////////////////////////



class Consttimer {

	var $timer = 		Array();
	var $timer_start = 	Array();
	var $timer_end = 	Array();

	var $add = "bcadd";

	function add($a,$b,$c) {
		if(function_exists("bcadd")) {
			return bcadd($a,$b,$c);
		} else {
			#saveerror("bc-functions not availible. Inaccurately Parsetimes.");
			return $a+$b;
		}
	}
	function sub($a,$b,$c) {
		if(function_exists("bcsub")) {
			return bcsub($a,$b,$c);
		} else {
			#saveerror("bc-functions not availible. Inaccurately Parsetimes.");
			return $a-$b;
		}
	}

	###############
	function getmicrotime() {
		$timer = explode(" ",microtime());
		return $this->add($timer[0],$timer[1],10);
	}
	###############
	function start($timerid) {
		$this->timer[$timerid] = $timerid;
		$this->timer_start[$timerid] = $this->getmicrotime();
	}
	###############
	function stop($timerid) {
		$this->timer_stop[$timerid] = $this->getmicrotime();
	}
	###############
	function gettime($timerid,$round=15) {
		return round($this->sub($this->timer_stop[$timerid],$this->timer_start[$timerid],10),$round);
	}
	###############
}
$consttimer 	= new ConstTimer;
?>
