<?php

// var_dump($_POST);
$conn = mysqli_connect("localhost","root","password","Roam");

$query = "SELECT * FROM `User Friends` WHERE `UserId` = $_POST[userId]";


$result = mysqli_query($conn, $query);

$rows = mysqli_num_rows($result);

$friendId = array();
while($friend_row = mysqli_fetch_assoc($result)){
	$friendId[] =  $friend_row['FriendID'];
};

$query = "SELECT * FROM `Users` WHERE `UserId` ";
$ids_for_query = "IN(";
foreach($friendId as $key => $val){

	if($key == (count($friendId)-1)){
		
		$ids_for_query = $ids_for_query.$val.")";
	}else{
		$ids_for_query = $ids_for_query.$val.", ";
	};
};
$query = $query.$ids_for_query;

$result = mysqli_query($conn, $query);

$rows = mysqli_num_rows($result);

$friend_list = array();

while($friends_entry = mysqli_fetch_assoc($result)){
	$friend_list[] = $friends_entry;
};
print(json_encode($friend_list));

?>