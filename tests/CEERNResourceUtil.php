<?php

/**
 * Helper function to make resource calls to CEERN site.
 */

class CEERNResourceUtil {
	
  private $ceen_location = 'http://localhost:8888/ceenapi/services/rest';
  private $public_key = '9665857d322055b074a977fe44686d47';
  private $private_key = '7352d97ef533d567e55c83f99b3c5cac';
	
	function __construct(){
        
  }	

  function CEERNResourceCall($url, $method = 'GET', $data = array(), $authenticate = FALSE, $resource_name = '', $output = TRUE) {
    $url = $this->ceen_location . $url;
    $nonce = uniqid(mt_rand());
    $timestamp = time() + (60 * 60 * 4); // Time adjusted for differences. API server is currently GMT -1.
  
    $hash_parameters = array($timestamp, $this->public_key, $nonce);
    $hash = hash_hmac("sha256", implode(';', $hash_parameters), $this->private_key);
  
    $ch = curl_init();
  
    if ($output && $data) {
      print "<br><b>Data Input</b>";
      krumo($data);
    }
  
    // if we're authenticating, we need to add info to the end of the query string. (i.e. - http://example.com/resource?test=1&[authinfo])
    if ($authenticate == TRUE) {
      if(!strpos($url, '?')) {
        $url .= '?';
      }
    
      $url = $url . implode('&', array(
        'hash=' . $hash,
        'public_key=' . $this->public_key,
        'timestamp=' . $timestamp,
        'nonce=' . $nonce,
      ));
    }
  
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-type: application/vnd.php.serialized',
      'Accept: application/vnd.php.serialized',
    ));
  
    // method switching
    switch (strtoupper($method)) {
      case 'POST':
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, serialize($data));
      break;
      case 'PUT':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, serialize($data));
      break;
      case 'DELETE':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
      break;
      case 'GET':
      default:
      break;
    }
  
    // grab URL and pass it to the browser
    $return = unserialize(curl_exec($ch));
    //$return = curl_exec($ch);
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    if ($output == TRUE) {
      print "<br><b>Data Call</b> - ".$method."  ".$resource_name." -- ".$url;
      krumo($return);
    }
  
    return $return;
  }
}
?>