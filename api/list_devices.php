<?php 
$dblink=db_connect("equipment");
$sql="Select `name`,`auto_id` from `devices` where `status`='active'";
$result=$dblink->query($sql) or
               die("<p>Something went wrong with $sql<br>".$dblink->error);
$devices=array();
if($result->num_rows>0){
	while ($data=$result->fetch_array(MYSQLI_ASSOC)){
		$devices[$data['auto_id']]=$data['name'];
}
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: Success';
	$jsonDevice=json_encode($devices);
    $output[]='MSG: '.$jsonDevice;
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
}
else{
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: Error';
	$jsonDevice=json_encode($devices);
    $output[]='MSG: Table empty';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
}
?>