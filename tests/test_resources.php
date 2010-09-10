<?php

class CEERNUnitTesting extends UnitTestCase {
	
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

  function testCreateResource() {
	global $temp_uuid;
	
	$resource = array(
	  'title' => 'Resource '.rand(),
	  'description' => 'This is my first resource.',
	  'type' => array(),
	  'time' => array(
	    'start' => '09/08/10 - 12:00 pm',
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
		'name' => 'Mary2 Last2',
		'email' => 'mary2@maryedith.com',
		'url' => 'http://mary2edith.com',
		'phone' => '800-777-2222',
	  ),
	  'grade_levels' => array(),
	  'education_continuum' => array(),
	  'participant_type' => array(),
	  'links' => array(),
	  'photos' => array(),
	  'user' => 'facb1e6e-aa8a-11df-8932-4040e8acc39d',

	);

    $resource = (object) $resource;
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location . '/resource.php', 'POST', $resource, TRUE, 'resource_resource.create');
    $this->assertTrue(isset($data->uuid));
	$temp_uuid = $data->uuid;

  }

  function testGetResources() {  

    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location . '/resource.php', 'GET', NULL, FALSE, 'resource_resource.index');
    $this->assertTrue(isset($data->stats));
//	$this->assertTrue(1==1);
  }

  function testGetResource( $uuid='db44a4fe-bc53-11df-8932-4040e8acc39d') {  

	global $temp_uuid;
//	$uuid = $temp_uuid;
	
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/resource'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'resource_resource.retrieve');
    $this->assertTrue(isset($data->title));
//	$this->assertTrue(1==1);
  }

  function testGetResourceTitle( $title='Sample') {  
    // '/?' or '?' is ok.  9/8/10
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/resource.php'.'?title='.$title, 'GET', NULL, FALSE, 'resource_resource.index');
//  print $data['resources'][0]['title'];
//  TODO:  check that $title is Truly matched in each title!
	$this->assertTrue($data['stats']['total']>0);

  }

  function testUpdateResource( $uuid='32c94870-bb90-11df-8932-4040e8acc39d') {  
	
	global $temp_uuid;
	$uuid = $temp_uuid;
	
	$resource = array(
	    'title' => 'Updated Resource',
	    'description' => 'describe my updated resource. It is awesome.',
	    'type' => array(
	      'Classroom Resources',
	    ),
	    'time' => array(
	      'start' => '09/08/2010 - 4:30pm',
	      'end' => '12/31/2010 - 6:30pm',
	    ),
	    'prerequisites' => 'A description of the prerequisites you need for this resource',
	    'location' => array (
	      'name' => 'Location Name',
	      'street' => '1600 Pennsylvania Avenue',
	      'additional' => 'Apt 2',
	      'city' => 'Washington',
	      'state' => 'DC',
	      'zip' => '46601',
	      'country' => 'United States',
	    ),
	    'language' => array(
	      'en',
	      'es',
	      'this can also be a single string instead of an array',
	    ),
	    'contact' => array(
	      'name' => "Moureen Doe",
	      'email' => "mau@example.com",
	      'url' => "http://example.com",
	      'phone' => "555-555-5555",
	    ),
	    'grade_levels' => array(

	    ),
	    'education_continuum' => array(

	    ),
	    'participant_type' => array(

	    ),
	    'links' => array(

	    ),
	    'photos' => array(

	    ),
	    'user' => 'http://example.com/services/rest/user/[user-uuid]',
	  );
	$resource = (object) $resource;

    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/resource'.'/'.$uuid.'.php', 'PUT', $resource, TRUE, 'resource_resource.update');
    $this->assertTrue($data==1);

  }

  // this second GET is to view the UPDATED Resource.
  function testReGetResource( $uuid='f8329646-a562-11df-8932-4040e8acc39d') {  

	global $temp_uuid;
	$uuid = $temp_uuid;

    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/resource'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'resource_resource.retrieve');
    $this->assertTrue(isset($data->title));
//	$this->assertTrue(1==1);
  }

  function testDeleteResource( $uuid='0af72026-ae3c-11df-8932-4040e8acc39d') {  

	global $temp_uuid;
    $uuid=$temp_uuid; 
    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location.'/resource'.'/'.$uuid.'.php', 'DELETE', NULL, TRUE, 'resource_resource.delete');
    $this->assertTrue($data==1);
  }

  function testGetResourceTypes() {  

    $data = $this->ceenRU->CEERNResourceCall($this->ceen_location . '/resource_types.php', 'GET', NULL, FALSE, 'resource_types_resource.index');
    $this->assertTrue(isset($data[3]['name'])); //hack
	//TODO: this should return $data['stats'] as other index functions do.
	//would prefer to use the following assert:
	//$this->assertTrue($data['stats']['total']>0);
  }

  /**
   * Helper function to make resource calls to CEERN site.
   */
/*  
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
*/
} // end class

?>
