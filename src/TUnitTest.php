<?php

namespace WebChemistry\Testing;

trait TUnitTest {

	/** @var \UnitTester */
	protected $tester;

	/** @var Services */
	protected $services;

	protected function setUp() {
		$this->services = new Services();

		parent::setUp();
	}

}