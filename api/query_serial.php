<?php 
$dblink=db_connect("equipment");
if ($sn==NULL)//missing serial number
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
$sql = "Select `device_id`,`manufacturer_id`,`serial_number` from `serials` where `serial_number`='$sn'";
$result=$dblink->query($sql) or
                            die("<p>Something went wrong with $sql<br>".$dblink->error);
if($result->num_rows >0){
	$data = array();
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
    	$data[] = $row;
}
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: Success';
	$jsonSerials=json_encode($data);
    $output[]='MSG: '.$jsonSerials;
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
else{
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Serial number does not exist.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
?>