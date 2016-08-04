<?php

namespace WebChemistry\Test;

trait TMethods {

	public function assertThrowException(callable $callback, $class) {
		$e = NULL;
		try {
			$callback();
		} catch (\Throwable $e) {
		} catch (\Exception $e) {
		}

		if ($e === NULL) {
			$this->fail("$class was expected, but none was thrown.");
		} else if (!$e instanceof $class) {
			$this->fail("$class was expected but got " . get_class($e) . ($e->getMessage() ? " ({$e->getMessage()})" : ''));
		}
	}

}
