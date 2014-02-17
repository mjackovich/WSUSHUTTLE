<?php
include '/home/content/23/11042423/html/wsushuttle/includes/functions.php';
	//downloadStaticGTFS($static_url, $static_destination);
	//extractStaticGTFS($static_destination, $static_directory);
	
	$line = fileRead($static_directory . "stops.txt");
	//read all the stops & place into database
	for ($x=1; $x < count($line); $x++){
		list($stop_id,$stop_name,$stop_desc,$stop_lat,$stop_lon,$stop_code) = explode(",", $line[$x]);
		echo $stop_name; 
		echo "\n";
	}
	//function fileToDatabase($static_directory . $filename)
	
?>