<?php

class BaseTest extends WP_UnitTestCase {

	function test_sample() {
		// replace this with some actual testing code
		$this->assertTrue( true );
	}

	function test_class_exists() {
		$this->assertTrue( class_exists( 'Selected_Posts_Widget') );
	}
	
	function test_get_instance() {
		$this->assertTrue( cdspw() instanceof Selected_Posts_Widget );
	}
}
