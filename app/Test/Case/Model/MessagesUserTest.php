<?php
App::uses('MessagesUser', 'Model');

/**
 * MessagesUser Test Case
 */
class MessagesUserTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.messages_user',
		'app.messages',
		'app.users'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MessagesUser = ClassRegistry::init('MessagesUser');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MessagesUser);

		parent::tearDown();
	}

}
