<?php

// var_dump($_POST);
$coordinates = $_POST['coordinates']['lat'].','.$_POST['coordinates']['lng'];

$name =  urlencode($_POST['theatre_info'][0]['name']);
$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='.$coordinates.'&radius=50000&types=movie_theater&name='.$name.'&key=AIzaSyCQNq766unXxvfCp1ZJ-aMCIT8tMmglOlo';

$ch = curl_init($url);
$data = curl_exec($ch);
curl_close($ch);

print_r($data);
?>