<?php
/**
 * User: kandrew1
 * Date: 2/16/14
 * Time: 6:49 PM
 */
switch($_GET['op']){
    case "updateRoutes":
        updateRoutes();
        exit;
    case "updateShapes":
        updateShapes();
        exit;
    case "updateStops":
        updateStops();
        exit;
    case "updateTrips":
        updateTrips();
        exit;
}
function updateStops(){
	//***********************************************************************
	//CONNECT TO THE DATABASE
	include("includes/dbconfig.php");
	$link = mysql_connect("$host", "$uname", "$pass")or die("cannot connect");
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("$dbname")or die("cannot select DB");
	//***********************************************************************
	include("includes/functions.php");
	$line = fileRead($static_directory . "stops.txt");
    //Truncate the stop table that holds the stops
    $sql = "truncate table stop";
    $query=mysql_query($sql) or die(mysql_error());
	for ($x=1; $x < count($line); $x++){
        list($stop_id,$stop_name,$stop_desc,$stop_lat,$stop_lon,$stop_code) = explode(",", $line[$x]);
        $sqlInsert = "insert into stop(id, name, `desc`, lat, lon, code) values('$stop_id', '$stop_name', '$stop_desc', '$stop_lat', '$stop_lon', $stop_code)";
        $queryInsert=mysql_query($sqlInsert) or die(mysql_error());
	}
	mysql_close($link);
}
function updateRoutes(){
    //***********************************************************************
    //CONNECT TO THE DATABASE
    include("includes/dbconfig.php");
    $link = mysql_connect("$host", "$uname", "$pass")or die("cannot connect");
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("$dbname")or die("cannot select DB");
    //***********************************************************************
    include("includes/functions.php");
    $line = fileRead($static_directory . "routes.txt");
    //Truncate the stop table that holds the stops
    $sql = "truncate table route";
    $query=mysql_query($sql) or die(mysql_error());
    for ($x=1; $x < count($line); $x++){
        list($route_id,$route_short_name,$route_long_name,$route_type,$route_color) = explode(",", $line[$x]);
        $sqlInsert = "insert into route(id, short_name, long_name, type, color) values('$route_id','$route_short_name','$route_long_name','$route_type','$route_color')";
        $queryInsert=mysql_query($sqlInsert) or die(mysql_error());
    }
    mysql_close($link);
}
function updateTrips(){
    //
    //***********************************************************************
    //CONNECT TO THE DATABASE
    include("includes/dbconfig.php");
    $link = mysql_connect("$host", "$uname", "$pass")or die("cannot connect");
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("$dbname")or die("cannot select DB");
    //***********************************************************************
    include("includes/functions.php");
    $line = fileRead($static_directory . "trips.txt");
    //Truncate the stop table that holds the stops
    $sql = "truncate table trip";
    $query=mysql_query($sql) or die(mysql_error());
    for ($x=1; $x < count($line); $x++){
        list($route_id,$service_id,$trip_id,$trip_headsign,$direction_id,$shape_id) = explode(",", $line[$x]);
        $sqlInsert = "insert into trip(route_id, service_id, trip_id, trip_headsign, direction_id, shape_id) values('$route_id','$service_id','$trip_id','$trip_headsign','$direction_id','$shape_id')";
        $queryInsert=mysql_query($sqlInsert) or die(mysql_error());
    }
    mysql_close($link);
}
function updateShapes(){
    //
    //***********************************************************************
    //CONNECT TO THE DATABASE
    include("includes/dbconfig.php");
    $link = mysql_connect("$host", "$uname", "$pass")or die("cannot connect");
    if (!$link) {
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("$dbname")or die("cannot select DB");
    //***********************************************************************
    include("includes/functions.php");
    $line = fileRead($static_directory . "shapes.txt");
    //Truncate the stop table that holds the stops
    $sql = "truncate table shape";
    $query=mysql_query($sql) or die(mysql_error());
    for ($x=1; $x < count($line); $x++){
        list($shape_id,$shape_pt_lat,$shape_pt_lon,$shape_pt_sequence) = explode(",", $line[$x]);
        $sqlInsert = "insert into shape(id,pt_lat,pt_lon,pt_sequence) values('$shape_id','$shape_pt_lat','$shape_pt_lon',$shape_pt_sequence)";
        $queryInsert=mysql_query($sqlInsert) or die(mysql_error());
    }
    mysql_close($link);
}
?>