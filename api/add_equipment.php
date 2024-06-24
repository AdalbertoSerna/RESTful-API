<?php
$dblink=db_connect("equipment");
//check if device is missing and if it is a number
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
//check to see if manufacturer is missing or if it is a number
else if ($mid==NULL || !ctype_digit($mid))//missing manufacturer id
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
else if ($sn==NULL)//missing serial number
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

$ch=curl_init("https://ec2-3-144-173-177.us-east-2.compute.amazonaws.com/api/query_serial");
$data="sn=".$sn;
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//ignore ssl
curl_setopt($ch, CURLOPT_POST,1);//tell curl we are using post
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//this is the data
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);//prepare a response
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'content-type: application/x-www-form-urlencoded',
    'content-length: '.strlen($data))
            );
$result=curl_exec($ch);
curl_close($ch);
$data=json_decode($result);

if($data[0] != 'Status: ERROR'){
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Serial number already exist.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();	
}
else{
	 $sql="Insert into `serials` (`device_id`,`manufacturer_id`,`serial_number`) values ('$did','$mid','$sn')";
	 $dblink->query($sql) or
			die("<p>Something went wrong with $sql<br>".$dblink->error);	
	header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: Success';
    $output[]='MSG: Device has been added.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}

?>