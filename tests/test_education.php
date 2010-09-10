<?php

class CEERNUnitTestingEducationStandards extends UnitTestCase {
	
  private $ceen_location = 'http://api.resourcecommons.org/services/rest';
  private $public_key = 'a545766537012063cce4aafef3e137f2';
  private $private_key = 'e4c746388aeceed2338474a56438bc7e';
//  private $public_key = '2df967ab004f241e9c10f03b7216396e';
//  private $private_key = '5397c5fc8715349f34dd71027b19422f';
  private $server_uuid = '69d6c734-a4bb-11df-8932-4040e8acc39d';

  function __construct() {
	 $this->ceenRU = new CEERNResourceUtil();
  }

  function setUp() {
    parent::setUp();
    
    /**
     * Add setup functionality here.
     */
  }
  
  function tearDown() {
    /**
     * Delete every resource from this server.
     */
     
    /**
     * Now, delete the users.
     */
  
    parent::tearDown();
  }
  
  function testGetEducationStandards() {  
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location . '/education_standards.php', 'GET', NULL, FALSE, 'educations_standards_resource.index');
    $this->assertTrue(isset($data->stats));
//	$this->assertTrue(1==1);
  }

  function testGetEducationStandard( $uuid='K_MD_1') {  
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/education_standards'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'education_standards_resource.retrieve');
    $this->assertTrue(isset($data->document));
//	$this->assertTrue(1==1);
  }

  /**
   * Helper function to make resource calls to CEERN site.
   */ 
/*
  function CEERNResourceCallEdu($url, $method = 'GET', $data = array(), $authenticate = FALSE, $resource_name = '', $output = TRUE) {
    $nonce = uniqid(mt_rand());
    $timestamp = time() + (60 * 60 * 4); // Time adjusted for differences. API server is currently GMT -1.
    
    $hash_parameters = array($timestamp, $this->public_key, $nonce, $resource_name);
    $hash = hash_hmac("sha256", implode(';', $hash_parameters), $this->private_key);
    
    $ch = curl_init();
    
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
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    if ($output == TRUE) {
      print "<br><b>Data Call</b> - ".$method."  ".$resource_name." -- ".$url;
      krumo($return);
    }
    
    return $return;
  }
*/
} // end class

?>
