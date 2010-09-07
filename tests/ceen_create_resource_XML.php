<?php

/**
 * Saves a new resource in the CEEN API.
 */

$resource = array(
  'title' => 'another Resource',
  'description' => 'This is my second resource.',
  'type' => array(),
  'time' => array(
    'start' => '08/22/10 - 12:00 pm',
	'end' => '12/31/10 - 12 pm'
  ),
  'prerequisites' => 'My prerequisites',
  'location' => array(
	'name' => 'My Resource Location',
	'street' => '100 Main St.',
	'additional' => '',
	'city' => 'Anytown',
	'state' => 'CA',
	'country' => 'USA',
  ),
  'language' => array( 'en' ),
  'contact' => array( 
	'name' => 'Mary3 Last3',
	'email' => 'mary3@maryedith.com',
	'url' => 'http://mary3edith.com',
	'phone' => '800-777-2222',
	),
	'grade_levels' => array(),
	'education_continuum' => array(),
	'participant_type' => array(),
	'links' => array(),
	'photos' => array(),
	'user' => '3c48ec46-aa94-11df-8932-4040e8acc39d'
);
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = 'a545766537012063cce4aafef3e137f2';
$private_key = 'e4c746388aeceed2338474a56438bc7e';
$nonce = uniqid(mt_rand());
$timestamp = time();
$resource_name = 'resource_resource.create';

$hash_parameters = array($timestamp, $public_key, $nonce, $resource_name);
$hash = hash_hmac("sha256", implode(';', $hash_parameters), $private_key);

$ch = curl_init();

$ceen_posturl = sprintf($ceen_location . 'resource?hash=%s&timestamp=%d&public_key=%s&nonce=%s', $hash, $timestamp, $public_key, $nonce);

print $ceen_posturl;

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_posturl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_POSTFIELDS, serialize($resource) );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-type: application/vnd.php.serialized',
  'Accept: application/vnd.php.serialized',
));

// grab URL and pass it to the browser
$postinfo = curl_exec($ch);

// close cURL resource, and free up system resources
curl_close($ch);
print "<pre>";
print_r($postinfo);
print "</pre>";

?>