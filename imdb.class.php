<?php
class IMDB{

	private $film_names = array();
	private $films = array();
	private $dir;
	private $db;
	
	function IMDB($dir, $type, $watched){
		include("database.php");
		$this->dir = $dir;
		$this->db = new mysqli($db_hostname, $db_username, $db_password, "MyVideos75");
		$this->db->set_charset("utf8");
		if (mysqli_connect_errno()) {
		    printf("Connect failed: %s\n", mysqli_connect_error());
		    exit();
		}
		if($type=="film"){
			$this->film_names = $this->db->query("SELECT c00 FROM `movie` WHERE 1");
			while($film_name = $this->film_names->fetch_object()){
				$film = new FILM($film_name->c00, $this->db);
				
				if($watched == '0' && $film->playCount >= 1){
					continue;
				}
				elseif($watched == '1' && $film->playCount == ''){
					continue;
				}
				array_push($this->films, $film);
			}
			$this->film_names->close();
		}
		$this->db->close();
	}
	function printList($sort_field, $watched){
		$last_tag;
		echo "<ul id=\"films\">";
		foreach($this->films as &$film){
			if($sort_field == 'director' && $last_tag != $film->$sort_field){
				echo "<li><h4>";
				echo "<br/>";
				echo $film->$sort_field;
				echo "</h4></li>";
			}
			$last_tag = $film->$sort_field;
			echo "<li>";
			$title = $film->title;
			$year = $film->year;
			$rate = $film->rating;
			$id   = $film->id;
			if($sort_field == 'director'){
				echo "<span class='hidden'>".$film->$sort_field."</span>";
			}
			if(isset($_SESSION['id']))
				$this->watched($id);
			echo "$year - [$rate] - "; 
			echo "<a href=\"javascript:loadContent('".addslashes($title)."');\">$title</a>\n";
			echo "</li>";
		}
		echo "</ul>";
	}
	function sort($sort_field, $sort_order){
		function compare($x, $y){
			global $sort_field;// name, year, rate
			global $sort_order;// 1 for increasing, -1 for descending
			if ( $x->$sort_field == $y->$sort_field )
					return 0;
			else if ( $x->$sort_field < $y->$sort_field )
					return -$sort_order;
			else
					return $sort_order;
		}
		usort($this->films, "compare");
	}
	static function rounddowntoten($theNumber) {
		if (strlen($theNumber)<2) {
			$theNumber=$theNumber;
		} else {
			$theNumber=substr($theNumber, 0, strlen($theNumber)-1) . "0";
		}
		return $theNumber;
	}
	function getSize(){
		return count($this->films);
	}
	
	function calculateGraph($rateGraph, $yearGraph){
		foreach ($this->films as &$film) {
			$rateGraph->addDataPoint($film->rating == "#.#" ? "-" : floor($film->rating));
			$yearGraph->addDataPoint($film->year == "####" ? "-" : IMDB::rounddowntoten($film->year));
		}
	}
}
?>