<?php

namespace WebChemistry\Testing;

use Nette\Utils\ObjectMixin;

trait TMagicGet {

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function __get(string $name) {
		$getter = 'get' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}

		$getter = 'is' . ucfirst($name);
		if (method_exists($this, $getter)) {
			return $this->$getter();
		}
		if (property_exists($this, $name)) {
			return $this->$name;
		}

		ObjectHelpers::strictGet(get_class($this), $name);
	}

}