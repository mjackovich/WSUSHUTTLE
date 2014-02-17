<?php
//global variables
$static_url = "http://api.fivepmtechnology.com/gtfs/GoogleTransitZip/24e5f43b-a701-422f-9219-0393ead1e0f8";
$static_directory = "/home/content/23/11042423/html/wsushuttle/gtfs-static/";
$static_destination = $static_directory . "google_transit.zip";
$realtime_url = "http://api.fivepmtechnology.com/gtfs/RealtimeVehiclePositions/24e5f43b-a701-422f-9219-0393ead1e0f8";
//global functions
function downloadStaticGTFS($url, $destination){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec ($ch);
	curl_close ($ch);
	$file = fopen($destination, "w+");
	fputs($file, $data);
	fclose($file);}
function extractStaticGTFS($openPath, $saveAs){
	$zip = new ZipArchive;
	if ($zip->open($openPath) === TRUE) {
		$zip->extractTo($saveAs);
		$zip->close();
		echo 'ok';
	} else {
		echo 'failed';
	}
}
function fileRead($filename){
	// Open the file
	$fp = @fopen($filename, 'r'); 
	// Add each line to an array
	if ($fp) {
		$array = explode("\n", trim(fread($fp, filesize($filename))));
	}
	return $array;}
?>