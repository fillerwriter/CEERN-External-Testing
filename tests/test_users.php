<?php

class CEERNUnitTestingUser extends UnitTestCase {
	
  private $ceen_location = 'http://api.resourcecommons.org/services/rest';
  private $public_key = 'a545766537012063cce4aafef3e137f2';
  private $private_key = 'e4c746388aeceed2338474a56438bc7e';
//  private $public_key = '2df967ab004f241e9c10f03b7216396e';
//  private $private_key = '5397c5fc8715349f34dd71027b19422f';
  private $server_uuid = '69d6c734-a4bb-11df-8932-4040e8acc39d';

  private $temp_uuid;

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
  
  function testCreateUser() {
	
	global $temp_uuid;
	
    $user = array(
      'first_name' => 'Mary'.rand(),
      'last_name' => 'Tester',
      'bio' => 'testing...testing...',
      'contact' => array(
        'mail' => 'maryceern@maryedith.com',
        'alternate_email' => 'example2@example.com',
        'website' => 'http://example.com',
        'street' => '444 Fourth St',
        'alternate' => 'Apartment 4',
        'city' => 'San Francisco',
        'state' => 'CA',
        'zip' => '93939',
        'county' => 'United States',
      ),
    );
    
    $user = (object) $user;
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location . '/user.php', 'POST', $user, TRUE, 'user_resource.create');
    $this->assertTrue(isset($data->uuid));
	$temp_uuid = $data->uuid;
  }
  

  function testGetUsers() {  
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location . '/user.php', 'GET', NULL, FALSE, 'user_resource.index');
    $this->assertTrue(isset($data->stats));
//	$this->assertTrue(1==1);
  }

  function testGetUser() {  
	global $temp_uuid;
	$uuid = $temp_uuid;
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/user'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'user_resource.retrieve');
    $this->assertTrue(isset($data->name));
  }

  function testGetUserPrivate( $uuid='7abe6fbc-aefb-11df-8932-4040e8acc39d') {  
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/user'.'/'.$uuid.'/private_info.php', 'GET', NULL, TRUE, 'user_resource.private_info');
    $this->assertTrue(isset($data->name));
  }

  function testUpdateUser( $uuid='7abe6fbc-aefb-11df-8932-4040e8acc39d') { 
	global $temp_uuid;
	// NOTE when UPDATE just-created user, uuid is not returned, but that user is updated!!  (spoils the assert below when this happens.)
	$uuid = $temp_uuid;
	 
	$resource = array(
	    'first_name' => 'Jerry',
		'last_name' => 'Chameleon',
	    'contact' => array(
	      'mail' => 'changed@maryedith.com',
	      'alternate_email' => 'chg2@example.com',
	      'website' => 'http://examplechg.com',
	      'street' => '1039 Washington St',
	      'alternate' => 'Apartment 2',
	      'city' => 'Franklin',
	      'state' => 'AR',
	      'zip' => '12345',
	      'county' => 'United States',
	    ),
	    'bio' => 'A bio for the user',
	  );
	$resource = (object)$resource;

    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/user'.'/'.$uuid.'.php', 'PUT', $resource, TRUE, 'user_resource.update');
    $this->assertTrue(isset($data->uuid));  // FAILS here when we updated the $temp_uuid
  }

  // this second GET is to view the UPDATED User.
  function testReGetUser() {  
	global $temp_uuid;
	$uuid = $temp_uuid;

    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/user'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'user_resource.retrieve');
    $this->assertTrue(isset($data->name));
  }


  function testDeleteUser( $uuid='3c48ec46-aa94-11df-8932-4040e8acc39d') {  

	global $temp_uuid;
	$uuid = $temp_uuid;
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/user'.'/'.$uuid.'.php', 'DELETE', NULL, TRUE, 'user_resource.delete');
    $this->assertTrue($data==1);
  }



  /**
   * Helper function to make resource calls to CEERN site.
   */ 
/* TODO: make this call an object, callable from all UnitTests
  function CEERNResourceCallUser($url, $method = 'GET', $data = array(), $authenticate = FALSE, $resource_name = '', $output = TRUE) {
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
