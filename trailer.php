<?php 
$name = $_GET['id'];

$trailer = file_get_contents("http://www.trailerapi.com/t/". urlencode($name));
if($trailer == "none"){
	echo"Sorry no movie found.";
}
else{
	echo $trailer . "<br />";
}
?>