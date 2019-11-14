<?php 
class Shambles {

	public $data; 

    	function __construct($a, $b, $c, $d) {
        	$this->$a = $a;
		$this->$b = $b;
		$this->$c = $c;
		$this->$d = $d;
		$this->data = array($a,$b,$c,$d);
		$this->shamble();
    	}	
   
	public function shamble(){
		//echo 'data'.$this->data;
		shuffle($this->data);
		//echo var_dump($this->data);
   	}
}
?>
