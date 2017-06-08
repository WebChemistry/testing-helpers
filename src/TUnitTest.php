<?php

namespace WebChemistry\Testing;

use WebChemistry\Testing\Components\Hierarchy\DomQuery;

trait TUnitTest {

	/** @var Services */
	protected $services;

	protected function setUp() {
		$this->services = new Services();

		$parent = get_parent_class($this);
		if ($parent !== FALSE && method_exists($parent, 'setUp')) {
			parent::setUp();
		}
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

	public function assertDomHas(DomQuery $domQuery, $selector) {
		if (!$domQuery->has($selector)) {
			$this->fail("Element $selector not found in DOM");
		}
	}

	public function assertDomNotHas(DomQuery $domQuery, $selector) {
		if ($domQuery->has($selector)) {
			$this->fail("Element $selector found in DOM");
		}
	}

}