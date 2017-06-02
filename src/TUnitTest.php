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

	public function assetThrownException(callable $function, $class, $message = NULL, $code = NULL) {
		$e = NULL;
		try {
			call_user_func($function);
		} catch (\Exception $e) {
		}

		if ($e === NULL) {
			$this->fail("$class was expected, but none was thrown");
		} elseif (!$e instanceof $class) {
			$this->fail("$class was expected but got " . get_class($e) . ($e->getMessage() ? " ({$e->getMessage()})" : ''));
		} elseif ($message && $message !== $e->getMessage()) {
			$this->fail("$class with a message matching {$message} was expected but got {$e->getMessage()}");
		} elseif ($code !== NULL && $e->getCode() !== $code) {
			$this->fail("$class with a code {$code} was expected but got {$e->getCode()}");
		}
	}

}