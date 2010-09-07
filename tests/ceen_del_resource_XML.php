<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = 'a545766537012063cce4aafef3e137f2';
$private_key = 'e4c746388aeceed2338474a56438bc7e';

$url = $ceen_location.'resource/0af72026-ae3c-11df-8932-4040e8acc39d';
$nonce = uniqid(mt_rand());
$timestamp = time();
$resource_name = 'resource_resource.delete';

$hash_parameters = array($timestamp, $public_key, $nonce, $resource_name);
$hash = hash_hmac("sha256", implode(';', $hash_parameters), $private_key);

$data = NULL;

$url = $url.'?'.'hash='.$hash.'&timestamp='.$timestamp.'&public_key='.$public_key.'&nonce='.$nonce;
print $url;

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

// grab URL and pass it to the browser
$response = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

print_r("<pre>");
print_r( $response );
print_r("</pre>");

?>
