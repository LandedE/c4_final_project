<?php
	
	// print_r($_POST);
	$origin_coordinates = $_POST['origin_coordinate'];
	$origin ='origins='.$origin_coordinates[0].','.$origin_coordinates[1]; 
	
	$destinations = $_POST['destination_array'];
	$destinations_query = '&destinations=';
	$url_start = 'https://maps.googleapis.com/maps/api/distancematrix/json?';

	for($i=0; $i<count($destinations); $i++){
		$destinations_query.=$destinations[$i]['lat'].','.$destinations[$i]['lng'].'|';

	};
	$destinations_query = substr($destinations_query, 0, -1);
	
	$url_end = '&language=en&key=AIzaSyCQNq766unXxvfCp1ZJ-aMCIT8tMmglOlo';
	$url = $url_start.$origin.$destinations_query.$url_end;
	
	$ch = curl_init($url);


	

	curl_exec($ch);
	curl_close($ch);


	

?>