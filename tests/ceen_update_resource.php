<?php

/**
 * Saves a new resource in the CEEN API.
 */

$resource = array(
  'title' => 'My Altered Resource',
  'description' => 'This is my altered resource.',
  'type' => array(),
  'time' => array(
    'start' => '09/14/10 - 9:00 am',
	'end' => '12/31/10 - 12 pm'
  ),
  'prerequisites' => 'My prerequisites',
  'location' => array(
	'name' => 'My Altered Location',
	'street' => '100 Main St.',
	'additional' => '',
	'city' => 'Anytown',
	'state' => 'CA',
	'country' => 'USA',
  ),
  'language' => array( 'en' ),
  'contact' => array( 
	'name' => 'Alter Denizen',
	'email' => 'denizen@resources.com',
	'url' => 'http://resources.com',
	'phone' => '800-777-2222',
  ),
  'grade_levels' => array(),
  'education_continuum' => array(),
  'participant_type' => array(),
  'links' => array(),
  'photos' => array(),
  'user' => '77bd63d4-a561-11df-8932-4040e8acc39d',
);
$resource = (object)$resource;
 
$ceen_location = 'http://api.resourcecommons.org/services/rest/';
$public_key = 'a545766537012063cce4aafef3e137f2';
$private_key = 'e4c746388aeceed2338474a56438bc7e';
$nonce = uniqid(mt_rand());
$timestamp = time() + (60 * 60 * 4);
$resource_name = 'resource_resource.update';

$hash_parameters = array($timestamp, $public_key, $nonce, $resource_name);
$hash = hash_hmac("sha256", implode(';', $hash_parameters), $private_key);

$ch = curl_init();

$ceen_posturl = sprintf($ceen_location . 'resource/32c94870-bb90-11df-8932-4040e8acc39d.php?hash=%s&timestamp=%s&public_key=%s&nonce=%s', $hash, $timestamp, $public_key, $nonce);

print $ceen_posturl;

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_posturl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, serialize($resource));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-type: application/vnd.php.serialized',
  'Accept: application/vnd.php.serialized',
));

// grab URL and pass it to the browser
$postinfo = unserialize(curl_exec($ch));

// close cURL resource, and free up system resources
curl_close($ch);
print "<pre>";
print_r($postinfo);
print "</pre>";

?>