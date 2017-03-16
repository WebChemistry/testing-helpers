<?php

namespace WebChemistry\Testing;

use Nette\Utils\ObjectMixin;

trait TMagicGet {

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name) {
		return ObjectMixin::get($this, $name);
	}

}