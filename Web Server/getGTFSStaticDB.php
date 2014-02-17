<?php
switch($_GET['op']){
    case "getRoutes":
        getRoutes();
        exit;
}
function getRoutes(){
	//***********************************************************************
	//CONNECT TO THE DATABASE
	include("includes/dbconfig.php");
	$link = mysql_connect("$host", "$uname", "$pass")or die("cannot connect");
	if (!$link){
		die('Could not connect: ' . mysql_error());
	}
	mysql_select_db("$dbname")or die("cannot select DB");
	//***********************************************************************
    $sql = "select * from stop";
	$query=mysql_query($sql) or die(mysql_error());
	$aList = array();
	while($result=mysql_fetch_array($query)){
		array_push($aList, $result);
	}
	echo json_encode($aList);
	mysql_close($link);
}
?>