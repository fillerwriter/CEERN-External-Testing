<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest';
$public_key = '';
$private_key = '';

$ch = curl_init();

// set URL and other appropriate options
//curl_setopt($ch, CURLOPT_URL, $ceen_location . '/resource');
curl_setopt($ch, CURLOPT_URL, $ceen_location . '/resource?Title=Test 2');
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
//  print "<p>" . $resource['title'] . ' - ' . $resource['uuid']."<br>";
//  print $resource['uri'] ."</p>\n";
//}

//Title does not work
// without the .php unserialized fails.  And can't find array in ln 28.
// it is xml