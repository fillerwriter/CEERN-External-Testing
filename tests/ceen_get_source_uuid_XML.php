<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = '';
$private_key = '';

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_location . '/source/97f26cde-a6fc-11df-8932-4040e8acc39d');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$source = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

print_r("<pre>");
print_r( $source );
print_r("</pre>");

?>