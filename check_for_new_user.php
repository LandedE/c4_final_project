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

$conn = mysqli_connect("localhost","root","password","Roam");

$query = "SELECT `Username`, `email`, `Avatar` FROM `Users` WHERE `UserID` = $_POST[user_id]";

$result = mysqli_query($conn, $query);

$current_id_match =  mysqli_num_rows($result);

$user_status = [];

if($current_id_match == 1){
	$user_status['new_user'] = false;
	while($row = mysqli_fetch_assoc($result)){
		$user_status[] = $row;
		
	}; 
	
	
}elseif($current_id_match == 0){
	$user_status['new_user'] = true;
	$user_status['userID'] = $_POST['user_id'];



	$basic_info_request = new FacebookRequest(
			$session,
			'GET',
			'/me'
			);
			$response = $basic_info_request->execute();
			$graphObject = $response->getGraphObject();
			
		$user_status['basic_profile'] = $graphObject->asArray();





	$query_to_create_user = "INSERT INTO `Users`(`UserID`, `Username`, `email`, `Avatar`) VALUES ";

		$array_of_profile_info = [
							$user_status['userID'],
							$user_status['basic_profile']['name'],
							$user_status['basic_profile']['email'],
							'http://graph.facebook.com/'.$_POST['user_id'].'/picture',
		   						];


		$all_profile_info[] = '\'' . implode('\',\'',$array_of_profile_info) . '\'';
				
		$query_values = '(' . implode('),(',$all_profile_info) . ')';

		$query_to_create_user .= $query_values;
		
		mysqli_query($conn, $query_to_create_user);
		print(mysqli_affected_rows($conn));


};

// print_r($array_of_profile_info);


?>