<?php

// http://data.tmsapi.com/v1.1/movies/showings?startDate=2015-07-21&lat=34.1198319&lng=-117.157426199999&api_key=vmgdezqrqhj977kubr4abpyp
$url = 'http://data.tmsapi.com/v1.1/movies/showings?startDate='.$_POST['date'].'&lat='.$_POST['coordinates']['lat'].'&lng='.$_POST['coordinates']['lng'].'&api_key=vmgdezqrqhj977kubr4abpyp';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
$data = curl_exec($ch);

curl_close($ch);


	


?>