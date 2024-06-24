<?php
include("../functions.php");
/*$url=$_SERVER['REQUEST_URI'];
header('Content-Type: application/json');
header('HTTP/1.1 200 OK');
$output[]='Status: ERROR';
$output[]='MSG: System Disabled';
$output[]='Action: None';
//log_error($_SERVER['REMOTE_ADDR'],"SYSTEM DISABLED","SYSTEM DISABLED: $endPoint",$url,"api.php");*/
$url=$_SERVER['REQUEST_URI'];
$path = parse_url($url, PHP_URL_PATH);
$pathComponents = explode("/", trim($path, "/"));
$endPoint=$pathComponents[1];
switch($endPoint)
{
    case "add_equipment":
        $did=$_REQUEST['did'];
        $mid=$_REQUEST['mid'];
        $sn=$_REQUEST['sn'];
        include("add_equipment.php");
        break;
	case "query_serial":
		$sn=$_REQUEST['sn'];
		include("query_serial.php");
		break;
    case "query_device":
		$did=$_REQUEST['did'];
		include("query_device.php");
		break;
	case "query_manuf":
		$mid=$_REQUEST['mid'];
		include("query_manf.php");
		break;
	case "modify_equipment":
		$did=$_REQUEST['did'];
        $mid=$_REQUEST['mid'];
        $sn=$_REQUEST['sn'];
		$sn2=$_REQUEST['sn2'];
		include("modify.php");
	case "query_device_by_name":
		$dname=$_REQUEST['dname'];
		include("query_device_by_name.php");
		break;
	case "query_manuf_by_name":
		$mname=$_REQUEST['mname'];
		include("query_manuf_by_name.php");
		break;
    case "add_device":
        $dname=$_REQUEST['dname'];
        include("add_device.php");
        break;	
    case "add_manufacturer":
        $mname=$_REQUEST['mname'];
        include("add_manufacturer.php");
        break;
    default:
        header('Content-Type: application/json');
        header('HTTP/1.1 200 OK');
        $output[]='Status: ERROR';
        $output[]='MSG: Invalid or missing endpoint';
        $output[]='Action: None';
        $responseData=json_encode($output);
        echo $responseData;
		echo $url;
        break;
}
die();
?>