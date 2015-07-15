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
  $data_length = count($std_class_data);
  for($i=0; $i<$data_length;$i++){
    $std_class_data_arr = (array) $std_class_data;
    $array_of_friend_data[] = $std_class_data_arr;
  };
  // print_r($std_class_data);
  
  print_r($std_class_data_arr);
 
};

// $conn = mysqli_connect("localhost","root","password","Roam");

// $query = "INSERT INTO `User Friends`(`UserId`, `FriendID`) VALUES "; 

// $values = "(".$_POST['userID'].','.$std_class_data_arr['id'].")";

// $query .= $values;



// print($query);

// $result = mysqli_query($conn, $query);

// $rows = mysqli_num_rows($result);
  





?>