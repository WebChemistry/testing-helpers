<?php

declare(strict_types=1);

namespace WebChemistry\Testing;

use WebChemistry\Testing\Components\Hierarchy\DomQuery;

trait TUnitTest {
	protected Services $services;

	protected function setUp(): void {
		$this->services = new Services();

		$parent = get_parent_class($this);
		if ($parent !== false && method_exists($parent, 'setUp')) {
			parent::setUp();
		}
	}

	public function assertThrownException(callable $function, string $class, ?string $message = null, $code = null): void {
		$this->addToAssertionCount(1);

		$e = null;
		try {
			call_user_func($function);
		} catch (\Exception $e) {
		}

		if ($e === null) {
			$this->fail("$class was expected, but none was thrown");
		} elseif (!$e instanceof $class) {
			$this->fail("$class was expected but got " . $e::class . ($e->getMessage() ? " ({$e->getMessage()})" : ''));
		} elseif ($message && $message !== $e->getMessage()) {
			$this->fail("$class with a message matching {$message} was expected but got {$e->getMessage()}");
		} elseif ($code !== null && $e->getCode() !== $code) {
			$this->fail("$class with a code {$code} was expected but got {$e->getCode()}");
		}
	}

	public function assertDomHas(DomQuery $domQuery, string $selector): void {
		$this->addToAssertionCount(1);

		if (!$domQuery->has($selector)) {
			$this->fail(sprintf('Element %s not found in DOM', $selector));
		}
	}

	public function assertDomNotHas(DomQuery $domQuery, string $selector): void {
		$this->addToAssertionCount(1);

		if ($domQuery->has($selector)) {
			$this->fail(sprintf('Element %s found in DOM', $selector));
		}
	}
}
