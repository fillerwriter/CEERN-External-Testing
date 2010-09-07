<?php

/**
 * Saves a new user in the CEEN API.
 */

$resource = array(
  'first_name' => 'Example',
  'last_name' => 'User',
  'bio' => 'This is an example user posted from some example code. Hopefully, developers will not use this code verbatim, and there won\'t be hundreds of these items on the API site.',
  'contact' => array(
    'mail' => 'exampleuser@example.com',
  ),
);
 
$ceen_location = 'http://ceenapi.djcase.com/services/rest/';
$public_key = '';
$private_key = '';
$nonce = uniqid(mt_rand());
$timestamp = time();
$resource_name = 'user_resource.create';

$hash_parameters = array($timestamp, $public_key, $nonce, $resource_name);
$hash = hash_hmac("sha256", implode(';', $hash_parameters), $private_key);

$ch = curl_init();

$ceen_posturl = sprintf($ceen_location . 'user.php?hash=%s&timestamp=%d&public_key=%s&nonce=%s', $hash, $timestamp, $public_key, $nonce);

print $ceen_posturl;

// set URL and other appropriate options
curl_setopt($ch, CURLOPT_URL, $ceen_posturl);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
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