<?php
	
	//var_dump($_POST['outing']);
	// print($_POST['outing'][0]);
	
	// print(json_encode($_POST['outing'][0]));
	
	$conn = mysqli_connect("localhost","root","","Roam");
	
	$query = "INSERT INTO `Event Affiliates`(`User Id`, `Event ID`, `Invited User`) VALUES ";
 	
	$num_of_invites = count($_POST['invitees']);


	for($i=0; $i<$num_of_invites; $i++){
		if($i==$num_of_invites-1){			
			$values =  "(";
			$values = $values.$_POST['user_id'].",";
			$values = $values.$_POST['event_id'].",";
			$values = $values.$_POST['invitees'][$i].")";
			$query = $query.$values;			
		}else{
			$values =  "(";
			$values = $values.$_POST['user_id'].",";
			$values = $values.$_POST['event_id'].",";
			$values = $values.$_POST['invitees'][$i]."),";
			$query = $query.$values;
			};		
	};
	
		
		
	$result = mysqli_query($conn, $query);

	print(mysqli_affected_rows($conn));

	$query = "INSERT INTO `Events`(`Outing ID`, `EventDetails`, `EventDate`, `Rating`, `Review Count`, `Address`, `Event Json`) VALUES ";

	
 	
	$num_of_events = count($_POST['outing']);


	for($i=0; $i<$num_of_events; $i++){
		if($i==$num_of_events-1){			
			$values =  "(";
			$values = $values.$_POST['event_id'].",";
			$values = $values."'".$_POST['outing'][$i]['description_text']."'".",";
			$values = $values."'".$_POST['date']."'".",";
			$values = $values.$_POST['outing'][$i]['rating'].",";	
			$values = $values.$_POST['outing'][$i]['review_count'].",";
			$values = $values."'".$_POST['outing'][$i]['location']['address'][0]."'".",";
			$values = $values."'".addslashes(json_encode($_POST['outing'][$i]))."'".")";
			$query = $query.$values;			
		}else{
			$values =  "(";
			$values = $values.$_POST['event_id'].",";
			$values = $values."'".$_POST['outing'][$i]['description_text']."'".",";
			$values = $values."'".$_POST['date']."'".",";
			$values = $values.$_POST['outing'][$i]['rating'].",";	
			$values = $values.$_POST['outing'][$i]['review_count'].",";
			$values = $values."'".$_POST['outing'][$i]['location']['address'][0]."'".",";
			$values = $values."'".addslashes(json_encode($_POST['outing'][$i]))."'"."),";
			$query = $query.$values;			
			};		
	};
	
		
	print($query);	
	$result = mysqli_query($conn, $query);
	print(mysqli_affected_rows($conn));
?>