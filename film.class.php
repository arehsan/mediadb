<?php
class FILM{
	
	public $movie_id;
	public $title;
	public $director;
	public $writer;
	public $actors;
	public $plot;
	public $poster;
	public $runtime;
	public $rating;
	public $votes;
	public $genre;
	public $released;
	public $year;
	public $rated;
	public $id;
	public $dir_name;
	
	public $db;

	function FILM($dir, $db){
		$this->db = $db;
		$this->dir_name = $dir;
		
		// get data from database
		$movie_name = $this->db->real_escape_string($dir);
		$result = $this->db->query("SELECT * FROM movieview WHERE c00 LIKE '" . $movie_name . "'");
		while($row = $result->fetch_object()){
				$this->movie_id = $row->idMovie;
				$this->title = $row->c00;
				$this->storyline = $row->c01;
				$this->plot = $row->c03;
				$this->votes = $row->c04;
				$this->rating = number_format($row->c05,1);
				$this->writer = $row->c06;
				$this->year = $row->c07;
				$this->poster = $row->c08;
				$this->id = $row->c09;
				$this->runtime = ($row->c11/60); //seconds
				$this->rated = $row->c12;
				$this->genre = $row->c14;
				$this->director = $row->c15;
				$this->org_title = $row->c16;
				$this->studio = $row->c18;
				$this->trailer = $row->c19;
				$this->thumbnail = $row->c20;
				$this->country = $row->c21;
				$this->path = $row->c22;
				$this->playCount = $row->playCount;
				//$this->actors = $row->actors; //actorlinkmovie
		}
	}
}
?>