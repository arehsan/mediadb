<?php 
include("film.class.php"); 

//get dir-name from post
$film_name = $_POST['id'];

//create database connection
include("database.php");
$db = new mysqli($db_hostname, $db_username, $db_password, "MyVideos75");
$db->set_charset("utf8");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

//create new film object
$film = new FILM($film_name, $db);
$query = "SELECT url FROM art WHERE media_id='$film->movie_id' AND media_type='movie' AND type='poster'";
$result = $db->query($query);
	if($result->num_rows > 0) {
	$row = $result->fetch_assoc();
	$film->poster = $row['url'];
}
echo "<img alt=\"\" src=\"" . $film->poster . "\" width=\"200\"/>";

//check if film is watched
$watched = ($film->playCount == 1) ? "Yes": "No";
?>

<!-- print film info -->
<h2><a href='http://www.imdb.com/title/<?php echo $film->id ?>'><?php echo $film->title ?></a></h2>
<h3><a href="trailer.php?id=<?php echo addslashes($film->title) ?>"
		onclick="return hs.htmlExpand(this, { objectType: 'ajax'} )">
	Trailer
</a></h3>

<table class="filminfo">
	<tr>
		<th>Director:</th>
		<td><?php echo $film->director ?></td>
	</tr>
	<tr>
		<th>Writer:</th>
		<td><?php echo $film->writer ?></td>
	</tr>
	<!--<tr>
		<th>Actors:</th>
		<td><?php echo $film->actors ?></td>
	</tr>-->
	<tr>
		<th>Plot:</th>
		<td><?php echo $film->plot ?></td>
	</tr>
	<tr>
		<th>Runtime:</th>
		<td><?php echo $film->runtime ?></td>
	</tr>
	<tr>
		<th>Rating:</th>
		<td><?php echo $film->rating ?></td>
	</tr>
	<tr>
		<th>Votes:</th>
		<td><?php echo $film->votes ?></td>
	</tr>
	<tr>
		<th>Genre:</th>
		<td><?php echo $film->genre ?></td>
	</tr>
	<tr>
		<th>Year:</th>
		<td><?php echo $film->year ?></td>
	</tr>
	<tr>
		<th>Rated:</th>
		<td><?php echo $film->rated ?></td>
	</tr>
	<tr>
		<th>IMDb-id:</th>
		<td><?php echo $film->id ?></td>
	</tr>
	<tr>
		<th>Watched:</th>
		<td><?php echo $watched ?></td>
	</tr>
</table>