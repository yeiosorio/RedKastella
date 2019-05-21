<?php
App::uses('MessagesType', 'Model');

/**
 * MessagesType Test Case
 */
class MessagesTypeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.messages_type'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->MessagesType = ClassRegistry::init('MessagesType');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->MessagesType);

		parent::tearDown();
	}

}
