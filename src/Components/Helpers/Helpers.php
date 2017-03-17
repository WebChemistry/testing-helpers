<?php

namespace WebChemistry\Testing\Components\Helpers;

use Nette\Utils\Strings;

class Helpers {

	public static function analyzeParams(array &$params, $controlName) {
		$controlName .= '-';
		foreach ($params as $name => $value) {
			if (!Strings::startsWith($name, $controlName)) {
				unset($params[$name]);
				$params[$controlName . $name] = $value;
			}
		}
	}

}
