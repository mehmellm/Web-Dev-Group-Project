<?php 
class Select_Creater {

    public $data;
    public $select_data; 

    	function __construct($array_data) {
        	$this->array_data = $array_data;
		    $this->creater();
    	}	
   
	public function creater(){
		//echo 'data'.$this->data;
		shuffle($this->data);
        //echo var_dump($this->data);
        $this->select_data = '<select name="sel">';
        foreach($this->array_data as $taker){
            $this->select_data = $this->select_data.'<option>'.$taker.'</option>';
        }
        $this->select_data = $this->select_data.'</select>';
        return $this->select_data;
    }
}
?>
