<?php

namespace WebChemistry\Testing;

class Reflection {

	/**
	 * @param string $className
	 * @param string $method
	 * @param array ...$params
	 * @return mixed
	 */
	public function callMethod($className, $method, ...$params) {
		$closure = $this->getMethodCallback($className, $method);

		return $closure(...$params);
	}

	/**
	 * @param string $className
	 * @param string $method
	 * @return \Closure
	 */
	public function getMethodCallback($className, $method) {
		$reflection = new \ReflectionMethod($className, $method);

		return $reflection->getClosure(new $className);
	}

}