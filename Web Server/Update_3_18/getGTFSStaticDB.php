<?php
switch($_GET['op']){
    case "getStops":
        getData("select * from stop");
        exit;
    case "getRoutes":
        getData("select * from route");
        exit;
    case "getTrips":
        getData("select * from trip");
        exit;
    case "getShapes":
        getData("select * from shape where id = '8b7fe932-b515-432c-8c64-7adbcec53e09-PATH' order by pt_sequence asc");
}
function getData($sql){
    //***********************************************************************
    //CONNECT TO THE DATABASE
    include("includes/dbconfig.php");
    $link = mysql_connect("$host", "$uname", "$pass")or die("cannot connect");
    if (!$link){
        die('Could not connect: ' . mysql_error());
    }
    mysql_select_db("$dbname")or die("cannot select DB");
    //***********************************************************************
    $query=mysql_query($sql) or die(mysql_error());
    $aList = array();
    while($result=mysql_fetch_array($query)){
        array_push($aList, $result);
    }
    echo json_encode($aList);
    mysql_close($link);
}
?>