<?php

class Graph{
	private $data;
	function graph($data){
		$this->data = $data;
	}
	function addDataPoint($point){
		$this->data[$point]++;
	}
	function makeData($arr){
		ksort($arr);
		$ret = "t:";
		foreach($arr as $key=>$data){
		$ret.="$data.0,";
		}
		return rawurlencode(substr($ret,0,-1));

	}
	function makeLabels($arr){
		ksort($arr);
		$ret = "0:";
		foreach($arr as $key=>$data){
		$ret.="|$key";
		}
		return rawurlencode($ret."|");
	}
	function makeRange($arr){
		// print_r($arr);
		$ret = "0,".ceil(max($arr)/20)*20;
		return rawurlencode($ret);
	}
	function makeRangeAxis($arr){
		$ret = "1,0,".(ceil(max($arr)/20)*20).",20";
		return rawurlencode($ret);
	}
	
	//remove leading empty values from graph
	function trim($array){
		foreach($array as $key => $value) { 
  			if($value == "") { 
    			unset($array[$key]); 
  			}if($value != ""){
  				return $array;
  			}
		}
	}
	
	function draw($title, $color){
		$this->data = $this->trim($this->data);
		return "<img alt=\"\"
				src=\"http://chart.apis.google.com/chart?cht=bvs&amp;chs=320x240&amp;chtt=".rawurlencode($title)."&amp;chd=".
				$this->makeData($this->data)."&amp;chxt=x,y&amp;chxl=".
				$this->makeLabels($this->data)."&amp;chxr=".
				$this->makeRangeAxis($this->data)."&amp;chds=".
				$this->makeRange($this->data)."&amp;chm=N*f0*,666666,0,-1,11&amp;chco=".rawurlencode($color)."\"/>";
	}	
}

?>