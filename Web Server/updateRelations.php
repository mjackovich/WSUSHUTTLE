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
	$line = fileRead($static_directory . "stops.txt");
	for ($x=1; $x < count($line); $x++){
        list($stop_id,$stop_name,$stop_desc,$stop_lat,$stop_lon,$stop_code) = explode(",", $line[$x]);
        $sql = "select count(*) as cnt from stop where id = '$stop_id'";
        $query=mysql_query($sql) or die(mysql_error());
        $result=mysql_fetch_array($query);
        if($result['cnt'] > 0){
            $sqlUpdate = "update stop set name = '$stop_name', `desc` = '$stop_desc', lat = '$stop_lat', lon = '$stop_lon', code = '$stop_code' where id = '$stop_id'";
            $queryUpdate=mysql_query($sqlUpdate) or die(mysql_error());
        }else{
            $sqlInsert = "insert into stop(id, name, `desc`, lat, lon, code) values('$stop_id', '$stop_name', '$stop_desc', '$stop_lat', '$stop_lon', $stop_code)";
            $queryInsert=mysql_query($sqlInsert) or die(mysql_error());
        }
	}
	mysql_close($link);
}
?>