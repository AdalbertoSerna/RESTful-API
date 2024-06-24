<?php
include("functions.php");
$dblink=db_connect("test");
//echo "Hello from php process $argv[1] about to process file:$argv[2]\n";
$fp=fopen("/home/ubuntu/parts/$argv[1]","r");
$count=1;
$time_start=microtime(true); 
echo "PHP ID:$argv[1]-Start time is: $time_start\n";
$sql=$dblink->prepare("Insert into `Error` (`line_number`,`device_type`,`manufacturer`,`serial_number`, `error`) values (?,?,?,?,?)");
$sql2=$dblink->prepare("Insert into `devices` (`device_type`,`manufacturer`,`serial_number`) values (?,?,?)");
$totalmissing_count = 0;
$char_count = 0;
$extrarow_count = 0;
$dupilcate_count = 0;
try{
	$dblink->autocommit(false);
while (($row=fgetcsv($fp)) !== FALSE) 
{	try{
	$error="Error: ";
	$missing_count =0;
		foreach($row as &$str){
			if(strpos($str, "'") !== false){
				$row=RemoveSpecialChar($str);
				$char_count++;
			}
		}
		
		if(count($row)>3){
			RemoveExcessColumns($row);
			$dupilcate_count++;
		}
	
		
		$sql3 = "SELECT * FROM `devices` WHERE `serial_number`='$row[2]'";
		$result = $dblink->query($sql3);
		if($result->num_rows >0){
			$error = $error."Duplicate Serial Number";
		}
		if($row[0] == ""){
			$error = $error."missing device type, ";
			$missing_count++;
		}
		if($row[1] == ""){
			$error = $error."missing manufacturer, ";
			$missing_count++;
		}
		if($row[2] == ""){
			$error = $error."missing serial number";
			$missing_count++;
		}
		if($missing_count == 3){
			$error = "Error: blank entry";
		}
		$totalmissing_count += $missing_count;
		if(strpos($error,"missing") !== false || strpos($error,"blank") !== false || strpos($error,"Duplicate") !== false){
			$sql->bind_param("issss",$count,$row[0],$row[1],$row[2],$error);
			$sql->execute();
		}
		else{

			$sql2->bind_param("sss",$row[0],$row[1],$row[2]);
			$sql2->execute();

		}
		if($count%500==0){
			$dblink->commit();
			$dblink->autocommit(false);
		}
		$count++;
		
	
	}
	catch(Exception $e){
		$dblink->rollback();
		echo "Exception: " . $e->getMessage() . "\n";
	}
	finally{
		unset($error, $missing_count, $row, $result);
	}
}
	$dblink->commit();
}
catch(Exception $e){
	$dblink->rollback();
	echo "Exception: " . $e->getMessage() . "\n";
}
finally{
	$dblink->autocommit(true);
}
$time_end=microtime(true);
echo "PHP ID:$argv[1]-End Time:$time_end\n";
$seconds=$time_end-$time_start;
$execution_time=($seconds)/60;
echo "PHP ID:$argv[1]-Execution time: $execution_time minutes or $seconds seconds.\n";
$rowsPerSecond=$count/$seconds;
echo "PHP ID:$argv[1]-Insert rate: $rowsPerSecond per second\n";
$total = $totalmissing_count + $dupilcate_count + $extrarow_count + $char_count;
echo "Total rows inserted: $count\n";
echo "Total errors: $total\n";
echo "Missing errors: $totalmissing_count\n";
echo "Duplicate serial number count: $dupilcate_count\n";
echo "Extra rows: $extrarow_count\n";
echo "Extra character: $char_count\n";
fclose($fp);
?>
