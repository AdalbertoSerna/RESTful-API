<?php 
$dblink=db_connect("equipment");
if ($mname==NULL || $mname=='')
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
$sql = "Select `auto_id`,`status` from `manufacturers` where `name`='$mname'";
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
	$jsonMan=json_encode($data);
    $output[]='MSG: '.$jsonMan;
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
else{
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Manufacturer does not exist.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}
?>