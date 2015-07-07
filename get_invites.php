<?php
 


$conn = mysqli_connect("localhost","root","","Roam");

$query = "SELECT * FROM `Event Affiliates` WHERE `Invited User` = $_POST[user_id]";


$result = mysqli_query($conn, $query);

$rows = mysqli_num_rows($result);

$array_of_events = [];

while($event_row = mysqli_fetch_assoc($result)){
 	$array_of_events[] = $event_row['Event ID'];
};

$num_of_events_invited_to = count($array_of_events);

$list_of_events_invited_to = [];
for($i=0; $i<$num_of_events_invited_to; $i++){
	
	$query = "SELECT * FROM `Events` WHERE `Outing ID` = ".$array_of_events[$i];
	
	$result = mysqli_query($conn, $query);
	
	//put in conditional to check if no rows are returned
	$rows = mysqli_num_rows($result);
	$individual_outing = [];
	while($event_in_outing = mysqli_fetch_assoc($result)){
		$individual_outing[] = $event_in_outing;

	};
	for($j=0;$j<count($individual_outing);$j++){
		$individual_outing[$j]['VenueJson'] = json_decode(stripslashes($individual_outing[$j]['VenueJson']),true);
		// json_encode(
	};
	$list_of_events_invited_to[] = $individual_outing;

};
print(json_encode($list_of_events_invited_to));

?>