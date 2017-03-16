<?php

namespace WebChemistry\Testing;

trait TUnitTest {

	/** @var \UnitTester */
	protected $tester;

	/** @var Services */
	protected $services;

	/** @var Reflection */
	protected $reflection;

	protected function setUp() {
		$this->services = new Services();
		$this->reflection = new Reflection();

		parent::setUp();
	}

}