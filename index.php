<?php 
error_reporting(E_ALL);
ini_set('display_errors', '1');
//session_start(); 
?>
<!DOCTYPE html>
<html lang="no">
	<head>
		<?php
		include("imdb.class.php");
		include("film.class.php");
		include("graph.class.php");
		?>
		
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<meta name="viewport" content="width = 900" />
		<title>Movies</title>
<!-- css -->
		<link href="css/imdb.css" rel="stylesheet" type="text/css" />
<!-- jquery	 -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
		
<!-- highslide -->
		<script type="text/javascript" src="highslide/highslide-full.packed.js"></script>
		<link rel="stylesheet" type="text/css" href="highslide/highslide.css" />
		<script type="text/javascript">
			hs.graphicsDir = 'highslide/graphics/';
			hs.showCredits = false;
			hs.align = 'center';
			hs.transitions = ['expand', 'crossfade'];
			hs.fadeInOut = true;
			hs.dimmingOpacity = 0.75;
		</script>

<!-- search script (needs jquery)-->
		<script type="text/javascript">
			var intval;
			$(function(){
				$("#match").bind("focus",function(){
					intval = setInterval(function(){
						var match = $("#match").val().toLowerCase();
						$("#films li").each(function(){
							if($(this).text().toLowerCase().indexOf(match)>=0){
								$(this).show();
							}else{
								$(this).hide();
							}
						});
					}, 200);
				}).bind("blur", function(){
					clearInterval(intval);
				});
			});
		</script>
		
<!-- send to chosen div-tag (needs jquery)-->
		<script type="text/javascript">
			function loadContent(sourceURL) {
				$('#graph').load("filminfo.php", {id: sourceURL});
			}
		</script>
		
		<?php
			$sort_field = array_key_exists('sort', $_GET) ? $_GET['sort'] : 'title';
			$sort_order = array_key_exists('dir', $_GET) ? $_GET['dir'] : 1;
			$watched = array_key_exists('watched', $_GET) ? $_GET['watched'] : -1;
	
			$yearGraph = new Graph(array(1910=>0,1920=>0,1930=>0,1940=>0,1950=>0,1960=>0,1970=>0,1980=>0,1990=>0,2000=>0,2010=>0));
			$rateGraph = new Graph(array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0,8=>0,9=>0));
		
		
			$imdb = new IMDB("/mnt/terra1/Public/Film", "film", $watched);
			$imdb->calculateGraph($rateGraph, $yearGraph);
		?>
	</head>
	<body onload="setTimeout(function() { window.scrollTo(0, 1) }, 100);">
		<div id="header">
			<h1>Movies</h1>
		
<!-- number of films in list -->
			<p><?php echo $imdb->getSize(); ?> movies</p>
		
<!-- sort choices -->
			<p>
			<?php
			echo "Sort by: ";
			echo "<a href='?watched=".$watched."&amp;sort=year&amp;dir=".($sort_order*-1)."'>year</a> | ";
			echo "<a href='?watched=".$watched."&amp;sort=rating&amp;dir=".($sort_order*-1)."'>rating</a> | ";
			echo "<a href='?watched=".$watched."&amp;sort=title&amp;dir=".($sort_order*-1)."'>title</a> | ";
			echo "<a href='?watched=".$watched."&amp;sort=director&amp;dir=".($sort_order*-1)."'>director</a>";
			echo "<br />";
			echo "View: &nbsp;&nbsp;&nbsp;";
			echo "<a href='?watched=-1&amp;sort=".$sort_field."&amp;dir=".$sort_order."'>all</a> | ";
			echo "<a href='?watched=1&amp;sort=".$sort_field."&amp;dir=".$sort_order."'>watched</a> | ";
			echo "<a href='?watched=0&amp;sort=".$sort_field."&amp;dir=".$sort_order."'>unwatched</a>";
			?>
			</p>
		
<!-- searchbox -->
			<input type="text" id="match" />
		
<!-- right sidebar -->
			<div id="right_sidebar">
<!-- graph -->
				<div id="graph">
					<?php echo $rateGraph->draw("Filmer per rating", "C6D9FD"); ?>
					<br />
					<?php echo $yearGraph->draw("Filmer per tiår", ""); ?>
				</div>
			</div>
		</div>
		
<!-- filmlist -->
		<div id="filmlist">
			<?php
			$imdb->sort($sort_field, $sort_order);
			$imdb->printList($sort_field, $watched);
			?>
		</div>
	</body>
</html>
