<?php

class CEERNUnitTesting extends UnitTestCase {
  private $server_uuid = '69d6c734-a4bb-11df-8932-4040e8acc39d';

  private $temp_uuid;
  private $user_uuid;

  function __construct() {
    $this->ceenRU = new CEERNResourceUtil();
    
    $user = array(
      'first_name' => 'Resource',
      'last_name' => 'Tester',
      'bio' => 'testing...testing...',
      'contact' => array(
        'mail' => 'test@test.com',
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
    
    $test_user = $this->ceenRU->CEERNResourceCall('/user.php', 'POST', $user, TRUE, 'user_resource.create', FALSE);
    $this->user_uuid = $test_user->uuid;
  }
  
  function __destruct() {
    $this->ceenRU->CEERNResourceCall('/reset.php', 'POST', NULL, TRUE);
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
     
  }

  function testCreateResource() {	
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
  	  'user' => $this->user_uuid,
  	  'fair_usage' => TRUE,
  	);

    $resource = (object) $resource;
    $data = $this->ceenRU->CEERNResourceCall('/resource.php', 'POST', $resource, TRUE, 'resource_resource.create');
    $this->assertTrue(isset($data->uuid));
    $this->temp_uuid = $data->uuid;
  }

  function testGetResources() {  
    $data = $this->ceenRU->CEERNResourceCall('/resource.php', 'GET', NULL, FALSE, 'resource_resource.index');
    $this->assertTrue(isset($data->stats));
  }

  function testGetResource() {  
    $uuid = $this->temp_uuid;
	
    $data = $this->ceenRU->CEERNResourceCall('/resource'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'resource_resource.retrieve');
    $this->assertTrue(isset($data->title));
//	$this->assertTrue(1==1);
  }

  function testGetResourceTitle() {  
    $data = $this->ceenRU->CEERNResourceCall('/resource.php'.'?title=Resource', 'GET', NULL, FALSE, 'resource_resource.index');
    $this->assertTrue($data['stats']['total']>0);
  }

  function testUpdateResource() {  
  	$uuid = $this->temp_uuid;
	
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
	    'user' => $this->user_uuid,
	    'fair_usage' => TRUE,
	  );
    
    $resource = (object) $resource;

    $data = $this->ceenRU->CEERNResourceCall('/resource'.'/'.$uuid.'.php', 'PUT', $resource, TRUE, 'resource_resource.update');
    $this->assertTrue(!empty($data));

  }

  // this second GET is to view the UPDATED Resource.
  function testReGetResource() {  
  	$uuid = $this->temp_uuid;

    $data = $this->ceenRU->CEERNResourceCall('/resource'.'/'.$uuid.'.php', 'GET', NULL, FALSE, 'resource_resource.retrieve');
    $this->assertTrue(isset($data->title));
//	$this->assertTrue(1==1);
  }

  function testDeleteResource() {  
    $uuid = $this->temp_uuid; 
    $data = $this->ceenRU->CEERNResourceCall('/resource'.'/'.$uuid.'.php', 'DELETE', NULL, TRUE, 'resource_resource.delete');
    $this->assertTrue($data==1);
  }

  function testGetResourceTypes() {  
    $data = $this->ceenRU->CEERNResourceCall('/resource_types.php', 'GET', NULL, FALSE, 'resource_types_resource.index');
  }
} // end class
