<?php
// Include Protobuf to decode file
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Message.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Registry.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Descriptor.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Field.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Enum.php');

//include_once(__DIR__ . '/../classes/gtfs-realtime.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/CodecInterface.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Codec/PhpArray.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Codec/Binary.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Codec/Binary/Writer.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Codec/Json.php');
include_once(__DIR__ . '/PEAR/PEAR/DrSlump/Protobuf/Codec/Binary/Reader.php');

// FivePM Technology API URLs
define('GTFS_REALTIME', 'http://api.fivepmtechnology.com/gtfs/RealtimeVehiclePositions/24e5f43b-a701-422f-9219-0393ead1e0f8');
define('GTFS_STATIC', 'http://api.fivepmtechnology.com/gtfs/GoogleTransitZip/24e5f43b-a701-422f-9219-0393ead1e0f8');

function GetGTFSReal($url, $save_location){
	$fp = fopen($save_location, 'w');
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	$data = curl_exec($ch);
	curl_close($ch);
	fclose($fp);	
}

// Check time of file, if it's more than 2 seconds get the newest file
$file_path = __DIR__ . '/gtfs-real/;
$file = "'gtfsrealtime.bin'";

if(!file_exists($file_path . $file) || filesize($file_path . $file) == 0 || (strtotime('now') >= filemtime($file_path . $file) + 2)){
	GetGTFSReal(GTFS_REALTIME, $file_path . $file);
	chmod($file_path . $file, 0777);
}

// Read current GTFS-realtime
$realtime = file_get_contents($file_path . $file);
$message = new transit_realtime\FeedMessage($realtime);

if(!method_exists($message->header, 'getTimeStamp') || ($message->header->getTimeStamp()+2) <= strtotime('now')){
	GetGTFSReal(GTFS_REALTIME, $file_path . $file);
	
	// Get the newly updated GTFS-realtime file
	$realtime = file_get_contents($file_path . $file);
	$message = new transit_realtime\FeedMessage($realtime);
}

// Get PHP Array of GTFS-realtime data
$codec = new DrSlump\Protobuf\Codec\PhpArray();	
$gtfs = $codec->encode($message);

// Set up array for return reponse, only need Timestamp, Vehicle ID, Longitude, and Latitude
$gtfs_return = array();
					 
// Manipulate GTFS array as needed
if(is_array($gtfs) && $gtfs['header']['timestamp'] >= 0){
	if(count($gtfs['entity']) > 0){
		foreach($gtfs['entity'] as $key => $vehicle){
			if(($vehicle['vehicle']['position']['latitude'] != 0 && $vehicle['vehicle']['position']['longitude'] != 0) && $vehicle['vehicle']['trip']['route_id'] != '')
    			$gtfs_return[] = array('vehicleId' => $vehicle['vehicle']['vehicle']['id'],
    								   'latitude' => $vehicle['vehicle']['position']['latitude'],
    								   'longitude' => $vehicle['vehicle']['position']['longitude'],
    								   'routeId' => $vehicle['vehicle']['trip']['route_id']);
		}
	}
}

if(isset($_GET['debug'])){
	echo '<pre>';
	print_r($gtfs);
	echo '</pre>';
	
	echo '<pre>';
	print_R($gtfs_return);
	echo '</pre>';	
}

header('Content-type: application/json');
echo json_encode($gtfs_return);
?>