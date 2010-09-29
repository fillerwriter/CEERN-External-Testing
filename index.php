<?php

require_once(dirname(__FILE__) . '/simpletest/autorun.php');
require_once(dirname(__FILE__) . '/krumo/class.krumo.php');

class CEERNUnitTesting extends UnitTestCase {
  private $ceen_location = 'http://api.resourcecommons.org/services/rest';
  private $public_key = '2df967ab004f241e9c10f03b7216396e';
  private $private_key = '5397c5fc8715349f34dd71027b19422f';
  private $server_uuid = '69d6c734-a4bb-11df-8932-4040e8acc39d';

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
  
  /**
   * Basic User CRUD functionality testing
   */
   
  function testUserCRUD() {
    $user = array(
      'first_name' => 'Brandon',
      'last_name' => 'Morrison Test 234aefasdf5',
      'bio' => 'I need a test user, stat.',
      'contact' => array(
        'mail' => 'brandontest@djcase.com',
        'alternate_email' => 'example2@example.com',
        'website' => 'http://example.com',
        'street' => '1039 Lincolnway',
        'alternate' => 'Apartment 2',
        'city' => 'Franklin',
        'state' => 'TN',
        'zip' => '12345',
        'county' => 'United States',
      ),
    );
    
    $user = (object) $user;
    /*$data = $this->CEERNResourceCall($this->ceen_location . '/user.php', 'POST', $user, TRUE, 'user_resource.create');
    $this->assertTrue(isset($data->uuid));*/
    
    $privateInfo = $this->CEERNResourceCall($this->ceen_location . '/user/' . '27307e6a-bc40-11df-8932-4040e8acc39d' . '/private_info.php', 'GET', array(), TRUE, 'user_resource.private_info');
  }
  
  /**
   * Basic Resource CRUD functionality testing
   */
  
  function testResourceCRUD() {
    $data = $this->CEERNResourceCall($this->ceen_location . '/resource.php');
    $this->assertTrue(1==1);
  }
  
  /**
   * Helper function to make resource calls to CEERN site.
   */
  
  function CEERNResourceCall($url, $method = 'GET', $data = array(), $authenticate = FALSE, $resource_name = '', $output = TRUE) {
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
    curl_setopt($ch, CURLOPT_HEADER, 1);
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
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
      break;
      case 'GET':
      default:
      break;
    }
    
    // grab URL and pass it to the browser
    $return = curl_exec($ch);
    
    $header_info = curl_getinfo($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    if ($output == TRUE) {
      print "Data Call - " . $url;
      krumo($header_info);
      krumo($return);
    }
    
    return $return;
  }
}