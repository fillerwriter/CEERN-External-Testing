<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = '';
$private_key = '';

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_location . '/resource/18d3c75e-ae3b-11df-8932-4040e8acc39d');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$resources = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

print_r("<pre>");
print_r( $resources );
print_r("</pre>");

//foreach ($resources['resources'] as $resource) {
//  print "<p>" . $resource['title'] . ' - ' . $resource['uuid'] . "</p>\n";
//}

//with resource.php this comes out as an array with elements 'stats' and 'resources'
// without hte .php unserialized fails.  And can't find array in ln 28.

?>