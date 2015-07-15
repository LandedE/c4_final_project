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
  var_dump($graphObject -> asArray());
  

	
};

// Add `use Facebook\FacebookSession;` to top of file
// print("<br>");
// var_dump($session);
?>