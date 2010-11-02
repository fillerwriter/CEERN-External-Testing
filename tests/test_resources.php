<?php

class CEERNUnitTesting extends UnitTestCase {
  private $user_uuid;

  function __construct() {
    $this->ceenRU = new CEERNResourceUtil();
    
    // Generate a user to own all the test results. We do it here instead of setup so that we only create one instead of one per test.
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
    $this->ceenRU->CEERNResourceCall('/reset.php', 'POST', NULL, TRUE, '', FALSE);
  }

  function setUp() {
    parent::setUp();
    
    /**
     * Add setup functionality here.
     */
     
  }
  
  function tearDown() {
    /**
     * Add teardown functionality here.
     */
     
  }

  /**
   * Tests basic CRUD functionality. In this function, we perform the following steps.
   * 
   * - Create a resource.
   * - Read a resource and ensure it's contents match what we sent.
   * - Update a resource.
   * - Read the updated resource, ensuring the contents match.
   * - Delete a resource.
   * - Attempt another read, ensuring it's unaccessible.   
   */

  function testCRUD() {	
  	$generated_resource = $this->generateResource(
      array(
        'defaults' => array(
          'fair_usage' => TRUE,
        ),  
  	  ));
  	
    $resource_info = $this->ceenRU->CEERNResourceCall('/resource.php', 'POST', $generated_resource, TRUE);
    $this->assertTrue(isset($resource_info->uuid), "UUID is set [%s]");
    
    $resource_from_database = $this->ceenRU->CEERNResourceCall('/resource/' . $resource_info->uuid . '.php', 'GET', NULL, FALSE);
    $resource_from_database = (object) $resource_from_database; // TMP until we fix utility function to return objects.
    $this->assertEqual($generated_resource->title, $resource_from_database->title, 'Title matches [%s]');
    // TODO: Flesh out rest of comparisions.
    
    $generated_resource = $this->generateResource(
      array(
        'defaults' => array(
          'fair_usage' => TRUE,
        ),  
  	  ));
    $generated_resource->uuid = $resource_info->uuid;
    
    $update_resource_info = $this->ceenRU->CEERNResourceCall('/resource/' . $resource_info->uuid . '.php', 'PUT', $generated_resource, TRUE);
    
    $resource_from_database = $this->ceenRU->CEERNResourceCall('/resource/' . $resource_info->uuid . '.php', 'GET', NULL, FALSE);
    $resource_from_database = (object) $resource_from_database; // TMP until we fix utility function to return objects.
    $this->assertEqual($generated_resource->title, $resource_from_database->title, 'Title matches after update. [%s]');
    // TODO: Flesh out rest of comparisions.
    
    $delete_resource_info = $this->ceenRU->CEERNResourceCall('/resource/' . $resource_info->uuid.'.php', 'DELETE', NULL, TRUE);
    $this->assertEqual($delete_resource_info, 1, 'Resource deleted [%s]');
    
    $resource_from_database = $this->ceenRU->CEERNResourceCall('/resource/' . $resource_info->uuid . '.php', 'GET', NULL, FALSE);
    $this->assertFalse($resource_from_database, 'No info returned from database after deleted [%s]');
  }

  /**
   * Tests index/search function for resources. In this function, we perform the following steps.
   *
   * - Create an internal list of x number of resources.
   * - Create an internal count of different values to search for from index.
   * - Add resources to index.
   * - For each searchable field, ensure that we're getting the correct number of items from the database.
   */
   
   /*  DESCRIPTION OF THE SEARCH/INDEXING FUNCTION FROM THE DOC:
    * Title (title) - Name of resource.
    * Language (language)- Language available for resource.
    * Source (source) - UUID for source site.
    * Resource Type (resource_type) - Resource Type.
    	Classroom Resources, Places To Go, Online Resources
    * Zip Code (zip) - Zip code search.
    * State (state) - State search.
    * Education Continuum (education_continuum) - Where on the education continuum does this fit?
    * Education Standard (edu_standard) - A single education standard
    * Update (update) - Filter out resources that were created or updated before this time. Useful to keep track of new posts.
    
    * Count (count) - # of items to display in a single query. Defaults to 20, maxes out at 100.
    * Page (page) - What page # do we want to list?
    */
   
  function testIndex() {
  
  }

  /**
   * Tests the accessibility of resource types.
   * TODO: Actually test something...
   */
  function testGetResourceTypes() {  
    $data = $this->ceenRU->CEERNResourceCall('/resource_types.php', 'GET', NULL, FALSE, 'resource_types_resource.index');
  }
  
  // Helper functions
  
  /**
   * Creates a CEERN resource object, ready to be sent to the API. Values are generated randomly.
   * 
   * @param $options
   *   array of different options for generation. Possible values...
   *   - defaults: an array of items to use as default values in the generated object. Layout 
   *     mirrors return value.
   *   - invalid: either the name of a field or an array of field names. If set, return object 
   *     will have invalid data for those fields. Useful for testing.
   *
   * @return
   *   The generated CEERN resource object.
   */
   
  function generateResource($options = array()) {
    $default_options = (isset($options['defaults'])) ? $options['defaults'] : array();
    $random_timestamp = rand();
  
    $resource = array(
  	  'title' => (isset($default_options['title'])) ? $default_options['title'] : $this->generateString(),
  	  'description' => (isset($default_options['description'])) ? $default_options['description'] : $this->generateString(50),
  	  'time' => array( // TODO: random time?
  	    'start' => (isset($default_options['time']['start'])) ? $default_options['time']['start'] : date('m/d/y h:i a', $random_timestamp),
  		  'end' => (isset($default_options['time']['end'])) ? $default_options['time']['end'] : date('m/d/y h:i a', $random_timestamp + 3600),
  	  ),
  	  'prerequisites' => (isset($default_options['prerequisites'])) ? $default_options['prerequisites'] : $this->generateString(),
  	  'location' => array(
    		'name' => (isset($default_options['location']['name'])) ? $default_options['location']['name'] : $this->generateString(),
    		'street' => (isset($default_options['location']['street'])) ? $default_options['location']['street'] : $this->generateString(),
    		'additional' => (isset($default_options['location']['additional'])) ? $default_options['location']['additional'] : $this->generateString(),
    		'city' => (isset($default_options['location']['city'])) ? $default_options['location']['city'] : $this->generateString(),
    		'state' => (isset($default_options['location']['state'])) ? $default_options['location']['state'] : $this->generateString(2), // TODO: pull from a list of valid states first.
    		'zip' => (isset($default_options['location']['zip'])) ? $default_options['location']['zip'] : rand(10000, 99999),
    		'country' => (isset($default_options['location']['country'])) ? $default_options['location']['country'] : strtolower($this->generateString(2)), // TODO: pull from a list of valid countries first.
  	  ), 
  	  'contact' => array( 
  		  'name' => (isset($default_options['contact']['name'])) ? $default_options['contact']['name'] : $this->generateString(),
  		  'email' => (isset($default_options['contact']['email'])) ? $default_options['contact']['email'] : $this->generateString(5) . '@' . $this->generateString(5) . '.com',
  		  'url' => (isset($default_options['contact']['url'])) ? $default_options['contact']['url'] : 'http://' . $this->generateString() . '.com',
  		  'phone' => (isset($default_options['contact']['phone'])) ? $default_options['contact']['phone'] : rand(100, 999) . '-' . rand(100, 999) . '-' . rand(1000, 9999),
  	  ),
  	  'user' =>  $this->user_uuid,
  	  'fair_usage' => (isset($default_options['fair_usage'])) ? $default_options['fair_usage'] : rand(0, 1), // Boolean.
  	);
  	
  	//TODO: Arrays to set in resource: language, grade_levels, educatation_continuum, education_standards_ participant_type, links
  	
  	// TODO: actual randomness with resource type.
  	if (isset($default_options['type'])) {
      $resource['type'] = $default_options['type']; 
  	} else {
      $resource['type'] = array(
        'Events',
      );
  	}

    if (isset($options['invalid'])) {
    
      if (!is_array($options['invalid'])) {
        $options['invalid'] = array($options['invalid']);
      }
      
      foreach($options['invalid'] as $field) {
        switch ($field) {
          case 'title':
          case 'description':
          case 'prerequisites':
          case 'city':
          case 'street':
          case 'address':
          case 'additional':
            // not sure how to have an actual invalid for these fields. Break through.
          break;
          case 'state':
          case 'zip':
          case 'country':
            // a random string here should suffice.
            $resource['location'][$field] = $this->generateString();
          break;
          case 'fair_usage':
            $resource['fair_usage'] = $this->generateString();
          break;
          case 'language':
          case 'resource_type':
          case 'education_continuum':
          case 'edu_standard':
            $resource[$field] = array($this->generateString());
          break;
          case 'start':
          case 'end':
            // Time fields.
            $resource['time'][$field] = $this->generateString();
          break;
        }
      }
    }
    
    return (object) $resource;
  }
  
  /**
   * Generates a random string of alpha-numeric characters.
   *
   * @param length
   *   Length of string to generate.
   * @return
   *   A random string of the requested length.
   */
   
  function generateString($length = 20) {
    $return = '';
    for($i = 0; $i < $length; $i++) {
      $range_set = rand(0, 2);
      switch ($range_set) {
        case 0:
          $return .= chr(rand(48,57));
        break;
        case 1:
          $return .= chr(rand(65,90));
        break;
        case 2:
          $return .= chr(rand(97, 122));
        break;
      }
    }
    
    return $return;
  } 
} // end class