<?php
$header = [];
$url = 'https://api.stubhub.com/search/catalog/events/v2?point=34.0500,-118.2500&units=mi&radius=50&categoryName=concert&limit=1000&date=2015-07-21T00:00%20TO%202015-07-26T23:59';
$header[] = 'Authorization: Bearer RKtLia4PDINi1su5n6efbRnQohka';
$ch = curl_init($url);

// curl_setopt($ch, CURLOPT_VERBOSE, 1);
// curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
$data = curl_exec($ch);



curl_close($ch);
?>