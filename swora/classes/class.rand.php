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



class randompasswort {
	//////////////////////////////////////////////////////////////
	// DefinitionsBereich
	var $do_alpha =	1;		// alphametrische Zeichen [A-Z]
	var $do_num =	1;		// numerische Zeichen [0-9]
	var $do_son = 	0;		// Sonderzeichen

	// Großkleinschreibung
	var $do_size = 	1;		// unterscheidung zwischen Groß-/Kleinschreibung
	//////////////////////////////////////////////////////////////


	var $pass="";

	function start() {
		mt_srand( (double) microtime() * 10000 );
		if($this->do_alpha) 	$k++;
		if($this->do_num) 	$k++;
		if($this->do_son) 	$k++;
		$start = mt_rand(0,--$k);
		return($start);
	}

	function alpha() {
		$alpha = "abcdefghijklmnopqrstuvwxyz";
		mt_srand( (double) microtime() * 10000 );
		$return = $alpha[rand(0,strlen($alpha))];

		if($this->do_size) {
			mt_srand( (double) microtime() * 10000 );
			$large = mt_rand(0,1);
			if($large)
				$return = strtoupper($return);
		}

		return($return);
	}

	function num() {
		mt_srand( (double) microtime() * 10000 );
		$return = mt_rand(0,9);
		return($return);
	}

	function son() 	{
		$son = "^°!\"§$%&/()=?`²³{[]}\´@+*~#'-_.:,;<>|µ";
		mt_srand( (double) microtime() * 10000 );
		$return = $son[rand(0,strlen($son))];
		return($return);
	}

	function generate($lang) {
		for($i=0;$i<$lang;$i++) {
			$sort = $this->start();
			    if($sort==0 && $this->do_alpha)	$this->pass = $this->pass.$this->alpha();
			elseif($sort==1 && $this->do_num)	$this->pass = $this->pass.$this->num();
			elseif($sort==2 && $this->do_son)	$this->pass = $this->pass.$this->son();
		}
	}


	// PUPLIC ---------------------------------------------

	function rand($laenge=0) {
		$this->generate($laenge);
		return $this->pass;
	}

}
?>
