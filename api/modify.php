<?php 
$dblink=db_connect("equipment");
//check if device is missing and if it is a number
if ($did !== 'none' && ($did==NULL || !ctype_digit($did)))//decive id is missing
{
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Invalid or missing device id.';
    $output[]='Action: query_device';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
//check to see if manufacturer is missing or if it is a number
else if ($mid !== 'none' && ($mid==NULL || !ctype_digit($mid)))//missing manufacturer id
{
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Invalid or missing manufacturer id.';
    $output[]='Action: query_manufacturer';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
else if ($sn==NULL || $sn=='')//missing serial number
{
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Invalid or missing serial number.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
else if(strlen($sn)>80){

    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Serial number too long.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();

}
else if(strlen($sn2)>80){

    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Serial number too long.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();

}
$sql = "Update `serials` set";


if($did !=='none'){
	$sql.="`device_id`='$did'";
}
if($mid !=='none'){
	$sql.=",`manufacturer_id`='$mid'";
}
if($sn2 !== ''){
	$sql.=",`serial_number`='$sn2'";
}
$sql.="where `serial_number` = '$sn'";

$dblink->query($sql) or
	die("<p>Something went wrong with $sql<br>".$dblink->error);
header('Content-Type: application/json');
header('HTTP/1.1 200 OK');
$output[]='Status: Sucess';
$output[]='MSG: Equipment modified.';
$output[]='Action: none';
$responseData=json_encode($output);
echo $responseData;
die();

?>