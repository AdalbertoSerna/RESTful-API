<?php
$dblink=db_connect("equipment");
if ($dname==NULL || $dname=='')//missing manufacturer id
{
    header('Content-Type: application/json');
    header('HTTP/1.1 200 OK');
    $output[]='Status: ERROR';
    $output[]='MSG: Invalid or missing device name.';
    $output[]='Action: query_manufacturer';
    $responseData=json_encode($output);
    echo $responseData;
    die();
}

$ch=curl_init("https://ec2-3-144-173-177.us-east-2.compute.amazonaws.com/api/query_device_by_name");
$data="dname=".$dname;
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
    $output[]='MSG: Devices already exist!.';
    $output[]='Action: none';
    $responseData=json_encode($output);
    echo $responseData;
    die();	
}
else{
	 $sql="Insert into `devices` (`name`,`status`) values ('$dname','active')";
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