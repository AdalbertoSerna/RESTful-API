<?php 
$dblink=db_connect("equipment");
if ($did==NULL || !ctype_digit($did))//decive id is missing
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
$sql = "Select `name`,`status` from `devices` where `auto_id`='$did'";
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
	$jsonDevice=json_encode($data);
    $output[]='MSG: '.$jsonDevice;
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
else{
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Device does not exist.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
?>