<?php
App::uses('Golfer', 'Model');

/**
 * Golfer Test Case
 */
class GolferTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.golfer'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Golfer = ClassRegistry::init('Golfer');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Golfer);

		parent::tearDown();
	}

}
