<?php
$conn = mysqli_connect("localhost","root","","Roam");
$query_for_event_ids = "SELECT `Event ID` FROM `Event Affiliates` WHERE `User Id` = $_POST[user_id]";
$result = mysqli_query($conn, $query_for_event_ids);
if(mysqli_num_rows($result) > 0 ){
$array_of_event_ids = [];
while($row = mysqli_fetch_assoc($result)){
	$array_of_event_ids[] = $row['Event ID'];
};
$i = 0;

$greatest = $array_of_event_ids[0];
while($i<count($array_of_event_ids)-1){
	
	if($greatest<=$array_of_event_ids[$i+1]){
		$greatest = $array_of_event_ids[$i+1];
		
	};
	$i++;
};

$next_id = [];
$next_id[] = $greatest+1;
}else{
	$next_id = [];
	$next_id[] = $_POST['user_id'];
};

print(json_encode($next_id));


?>