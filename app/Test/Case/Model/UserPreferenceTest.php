<?php
App::uses('UserPreference', 'Model');

/**
 * UserPreference Test Case
 */
class UserPreferenceTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_preference',
		'app.users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserPreference = ClassRegistry::init('UserPreference');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserPreference);

		parent::tearDown();
	}

}
