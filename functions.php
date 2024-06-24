<?php 

function db_connect($db)
{
	$username="webuser";
	$password="jb)MZX1[H*nshs00";
	$host="localhost";
	$dblink = new mysqli($host,$username,$password,$db);
	return $dblink;
}

function RemoveSpecialChar($str){

		$str = str_replace(array('\'', '"',';','<','>'),'',$str);
	
	return $str;
}
function RemoveExcessColumns($arr){
	$size = count($arr);
	if($size > 3){
    if($arr[0] == '' && $arr[$size-1] == ''){
    	$arr=array_slice($arr,1,3);
    }
    elseif($arr[0] == '') {
    	$arr=array_slice($arr,-3,3);
    }
    else{
    	$arr=array_slice($arr,-4,3);
    }
}
	return $arr;
}
function redirect($uri){
	?>
	<script type="text/javascript">
	<!--
		document.location.href="<?php echo $uri; ?>";
	-->
		</script>
<?php die;
}
?>