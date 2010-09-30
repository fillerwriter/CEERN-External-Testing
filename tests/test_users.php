<?php

class CEERNUnitTestingUser extends UnitTestCase {
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
    $user = array(
      'first_name' => 'Mary',
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
    $data = $this->ceenRU->CEERNResourceCall('/user.php', 'POST', $user, TRUE, 'user_resource.create');
    $this->assertTrue(isset($data->uuid));
	  $this->temp_uuid = $data->uuid;
  }
  

  function testGetUsers() {  
    $data = $this->ceenRU->CEERNResourceCall('/user.php', 'GET', NULL, FALSE, 'user_resource.index');
    $this->assertTrue(isset($data->stats));
  }

  function testGetUser() {  
	$uuid = $this->temp_uuid;
	
    $data = $this->ceenRU->CEERNResourceCall('/user'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'user_resource.retrieve');
    $this->assertTrue(isset($data->name));
  }

  function testGetUserPrivate() {  
	
    $data = $this->ceenRU->CEERNResourceCall('/user'.'/'.$this->temp_uuid.'/private_info.php', 'GET', NULL, TRUE, 'user_resource.private_info');
    $this->assertTrue(isset($data->name));
  }

  function testUpdateUser() {
  	$uuid = $this->temp_uuid;
  	 
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

    $data = $this->ceenRU->CEERNResourceCall('/user'.'/'.$uuid.'.php', 'PUT', $resource, TRUE, 'user_resource.update');
    $this->assertTrue(isset($data->uuid));
    $this->assertTrue($data->uuid == $uuid);
  }

  // this second GET is to view the UPDATED User.
  function testReGetUser() {  
	$uuid = $this->temp_uuid;

    $data = $this->ceenRU->CEERNResourceCall('/user'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'user_resource.retrieve');
    $this->assertTrue(isset($data->name));
  }


  function testDeleteUser() {  
    $uuid = $this->temp_uuid;
	
    $data = $this->ceenRU->CEERNResourceCall('/user'.'/'.$uuid.'.php', 'DELETE', NULL, TRUE, 'user_resource.delete');
    $this->assertTrue($data==1);
    
    $data = $this->ceenRU->CEERNResourceCall('/user'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'user_resource.retrieve');
    $this->assertFalse(isset($data->name));
  }
} // end class

?>
