<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://ceenapi.djcase.com/services/rest/';
$public_key = '';
$private_key = '';

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_location . '/resource.php/?Title=TEST');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$resources = unserialize(curl_exec($ch));

// close cURL resource, and free up system resources
curl_close($ch);

//print_r("<pre>");
//print_r( $resources );
//print_r("</pre>");

foreach ($resources['resources'] as $resource) {
  print "<p>" . $resource['title'] . ' - ' . $resource['uuid'] . "</p>\n";
}

//with resource.php this comes out as an array with elements 'stats' and 'resources'
// without hte .php unserialized fails.  And can't find array in ln 28.