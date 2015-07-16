<?php
// var_dump($_POST);
define('FACEBOOK_SDK_V4_SRC_DIR', 'facebook-php-sdk-v4/src/Facebook/');
require __DIR__ . '/facebook-php-sdk-v4/autoload.php';

// Make sure to load the Facebook SDK for PHP via composer or manually

use Facebook\FacebookSession;
// add other classes you plan to use, e.g.:
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookJavaScriptLoginHelper;
use Facebook\FacebookRequestException;

FacebookSession::setDefaultApplication('850571841703710', '453b01d05d121a19320130caf036bc7c');


// Add `use Facebook\FacebookJavaScriptLoginHelper;` to top of file

$session = new FacebookSession($_POST['token']);


if ($session) {
  	print('session exists');
  	$request = new FacebookRequest(
  			$session, 
  			'GET', 
  			'/me/friends'
  	);
	$response = $request->execute();
	$graphObject = $response->getGraphObject();
	
	var_dump($graphObject);
  $array_of_friends = $graphObject -> asArray();
  $array_of_friend_data = [];
  $std_class_data = $array_of_friends['data'];
  print_r($std_class_data);
  $data_length = count($std_class_data);
  print($data_length);
  for($i=0;$i<$data_length;$i++){
    $std_class_data_arr = (array) $std_class_data[$i];
    print_r($std_class_data_arr);
    $array_of_friend_data[] = $std_class_data_arr;
  };
 
  
  print_r($array_of_friend_data);
  $array_of_friend_data_length = count($array_of_friend_data);
  $array_of_friend_ids = [];
  for($i=0; $i<$array_of_friend_data_length;$i++){
    $array_of_friend_ids[] = $array_of_friend_data[$i]['id'];
  };
 


$conn = mysqli_connect("localhost","root","password","Roam");

for($i=0;$i<count($array_of_friend_ids);$i++){

  $check_if_friend_query = "SELECT `UserId`, `FriendID` FROM `User Friends` WHERE `UserId` = ". $_POST['userID'] ."&& `FriendID` =".$array_of_friend_ids[$i];
  print_r($check_if_friend_query);
  $result = mysqli_query($conn, $check_if_friend_query);
  $rows = mysqli_num_rows($result);
  print($rows);
  if($rows == 0){
    print('in if statement rows == 0');
    $insert_friend_query = "INSERT INTO `User Friends`(`UserId`, `FriendID`) VALUES "."(".$_POST['userID'].','.$array_of_friend_ids[$i].")";
    print($insert_friend_query);
    $result = mysqli_query($conn, $insert_friend_query);
    print(mysqli_affected_rows($result));

  };

};



// $values = "(".$_POST['userID'].','.$std_class_data_arr['id'].")";

// $query .= $values;



// print($query);

// $result = mysqli_query($conn, $query);

// $rows = mysqli_num_rows($result);
  
};




?>