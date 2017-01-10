<?php
// NodeMCU will always check this URL /ask.php?id=NM312
date_default_timezone_set("Asia/Kolkata");
	
if(isset($_GET['id'])){
  $val = $_GET['id'];

  $deviceId = substr($val,0,3);
  $userId=substr($val,3,strlen($val)-3);

$db_file = file_get_contents("db.json");
$data = json_decode($db_file);
$filename="";
foreach ($data->login as $i => $values) {
	if($values->id == $userId)
		$filename=$values->filename;
}  

if ($filename!=""){
	$json_file = file_get_contents($filename);
	$stats = json_decode($json_file);
	$value="a";
	//echo $filename;//echo dechex(substr($stat->pin16,0,1)*8+substr($stat->pin05,0,1)*4+substr($stat->pin04,0,1)*2+substr($stat->pin00,0,1));
	//$id=$_GET['id'];
	foreach ($stats->status as $i => $stat) {
		if(substr($stat->id,0,3) == $deviceId){
		$value= chr(substr($stat->pin16,0,1)*8+substr($stat->pin05,0,1)*4+substr($stat->pin04,0,1)*2+substr($stat->pin00,0,1)+97);
		$stat->lastseen=date('D M d, Y h:i:s A');
		} 
	}
	echo $value;
	$json = json_encode($stats, JSON_PRETTY_PRINT);
file_put_contents($filename, $json); 
//echo $json;
}
else
 echo "a";
}
	
			
if(isset($_GET['u'])&&isset($_GET['p'])){
 	$u=$_GET['u']; 
	$p=$_GET['p'];
	require_once 'class.user.php';
	$user = new USER();
	
	$stmt = $user->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
   $stmt->execute(array(":email_id"=>$u));
   $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
 if($stmt->rowCount() >0)
   {
    if($userRow['userStatus']=="perm")
    {
     if($userRow['userPass']==md5($p))
     	echo "[]".$userRow['userID'];
     else 
    	echo "[]0";
    }else 
	echo "[]0";
   }else 
	echo "[]0";
}

	
if(isset($_GET['t'])){
		//echo"<br> Time Now : ".date('l d/m/Y g:i:s A');	
		$date1 = array(date('j'),date('m'),date('Y')%100,date('G'),date('i'),date('s'));
	foreach($date1 as $i=>$values){
		if ($values>9 && $values<36){
			$values+=87;//$values=$date1[$i];
			echo chr($values);
		}
		elseif ($values>35){
			$values+=29;
			echo chr($values);
		}
		else
			echo (int)$values;
		}
	}
?>
