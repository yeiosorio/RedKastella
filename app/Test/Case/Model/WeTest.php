<?php
App::uses('We', 'Model');

/**
 * We Test Case
 */
class WeTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.we'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->We = ClassRegistry::init('We');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->We);

		parent::tearDown();
	}

}
