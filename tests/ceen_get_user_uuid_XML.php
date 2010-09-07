<?php

/**
 * Sets all of our variables needed to grab resources.
 */
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = '';
$private_key = '';

$ch = curl_init();

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_location . 'user/b46bf36c-aa35-11df-8932-4040e8acc39d');
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// grab URL and pass it to the browser
$users = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);

print_r("<pre>");
print_r( $users );
print_r("</pre>");

//foreach ($users['users'] as $user) {
//  print "<p>" . $user['name'] . ' - ' . $user['uuid'] . "</p>\n";
//}

