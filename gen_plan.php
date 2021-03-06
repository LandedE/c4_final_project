<?php

$num_params = (count($_POST)-1);
$all_results= array();
// var_dump($_POST);

/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */
// Enter the path that the oauth library is in relation to the php file
require_once('lib/OAuth.php');
// Set your OAuth credentials here  
// These credentials can be obtained from the 'Manage API Access' page in the
// developers documentation (http://www.yelp.com/developers)
$CONSUMER_KEY = '7vgQzif6o95bcvM28skxcw';
$CONSUMER_SECRET = '54CPSG1ziD9qbcOEhiy2KEyYUv4';
$TOKEN = 'yWaXnF0tv9MwEFUyLelD_xbnoof8aOom';
$TOKEN_SECRET = 'QQ0TMavmzWVZoQCll0988QZh3i8';
$API_HOST = 'api.yelp.com';
$DEFAULT_TERM = 'dinner';
$DEFAULT_LOCATION = 'Redlands';
$DEFAULT_COORDINATES = null;
$SEARCH_LIMIT = 10;
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';

/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "http://" . $host . $path;
    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);
    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);
    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    $ch = curl_init($signed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    
    return $data;
}
/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location) {
    $url_params = array();
    
    $url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    
    $url_params['location'] = $location?: $GLOBALS['DEFAULT_LOCATION'];
    if($_POST['location']['coordinates'] !== ''){
        $url_params['cll'] = $_POST['location']['coordinates'];
    };

    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);

}
/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . $business_id;
    
    return request($GLOBALS['API_HOST'], $business_path);
}
/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */
function query_api($term, $location) {     
    $response = json_decode(search($term, $location));
    $business_id = $response->businesses[0]->id;
    
    // print sprintf(
    //     "%d businesses found, querying business info for the top result \"%s\"\n\n",         
    //     count($response->businesses),
    //     $business_id
    // );
    // print("<br>");
    $array_of_results = array();
    $return_businesses = $response->businesses;
    $num_of_results = count($return_businesses);
    // var_dump($return_businesses);
    // echo json_encode($return_busineses[0]);
    for($i=0;$i<$num_of_results;$i++){
        $array_of_results[] = $return_businesses[$i];
        // if(null == ($return_businesses[$i]->phone)){
        //     print('not set');
        //     continue;
        // };
       
    // $new_business = array();
    // // print_r($return_businesses[$i]);
    // // print_r($return_businesses[$i]->name);

    // $new_business['name'] = $return_businesses[$i]->name;
    // // print("<br>");
    
    // $new_business['image'] = $return_businesses[$i]->image_url;
       
    
    // // print_r($return_businesses[$i]->snippet_image_url);
    // // print("<br>");
    // $new_business['rating'] = $return_businesses[$i]->rating;
    // // print_r($return_businesses[$i]->rating);
    // // print("<br>");
    // $new_business['website'] = $return_businesses[$i]->url;
    // // print_r($return_businesses[$i]->url);
    // // print("<br>");
    
    
    
            
      
    // $new_business['phone'] = $return_businesses[$i]->phone;
        
  
    // // print_r($return_businesses[$i]->phone);
    // // print("<br>");
    // $new_business['review_count'] = $return_businesses[$i]->review_count;
    // // print_r($return_businesses[$i]->review_count);
    // // print("<br>");
    // $new_business['location'] = $return_businesses[$i]->location->coordinate;
    // // print_r($return_businesses[$i]->location->coordinate);
    // // print("<br>");
    // $new_business['categories'] = $return_businesses[$i]->categories;
    // // print_r($return_businesses[$i]->categories);
    // // print("<br>");
    // var_dump($new_business);
    // $new_business;
    
    }
    return $array_of_results;
    // $response = get_business($business_id);
    
    // print sprintf("Result for business \"%s\" found:\n", $business_id);
    // print "$response\n";
}
/**
 * User input is handled here 
 */
$longopts  = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);
$term = $options['term'] ?: '';
$location = $options['location'] ?: '';

// var_dump($all_results);

for($i=0; $i<$num_params; $i++){
    
    if($_POST[$i]==null){
        continue;
    }
        $DEFAULT_LOCATION = $_POST['location']['city'];
        $DEFAULT_TERM = $_POST[$i];
        $all_results[$i] = query_api($term, $location);
         
};

// echo json_encode($all_results); 
// var_dump($all_results);
echo json_encode($all_results);








?>