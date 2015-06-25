<?php
	
	
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



	$query_for_event_owner_username = "SELECT `Username`, `Avatar` FROM `Users` WHERE `UserID` = $_POST[user_id]";

	
	$username_result = mysqli_query($conn, $query_for_event_owner_username);

	$username_row = mysqli_fetch_assoc($username_result);

	$username = $username_row['Username'];
	$user_pic = $username_row['Avatar'];



	$query = "INSERT INTO `Events`(`Outing ID`, `EventOwner`, `EventOwnerPicture`, `EventDetails`, `EventDate`, `Rating`, `Review Count`, `Address`, `VenueJson`) 
			 VALUES ";


 	
	$num_of_events = count($_POST['outing']);
	$all_events = [];
	for($i=0; $i<$num_of_events; $i++){
		$fields_to_use = [
			$_POST['event_id'],
			$username,
			$user_pic,
			$_POST['outing'][$i]['description_text'],
			$_POST['date'],
			$_POST['outing'][$i]['rating'],
			$_POST['outing'][$i]['review_count'],
			$_POST['outing'][$i]['location']['address'][0],
			addslashes(json_encode($_POST['outing'][$i])),

		];
		$all_events[] = '\'' . implode('\',\'',$fields_to_use) . '\'';
		
	};
	$query_values = '(' . implode('),(',$all_events) . ')';
	$query .= $query_values;
		
	print($query);	
	$result = mysqli_query($conn, $query);
	print(mysqli_affected_rows($conn));
?>

