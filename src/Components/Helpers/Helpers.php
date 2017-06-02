<?php

namespace WebChemistry\Testing\Components\Helpers;

use Nette\Utils\Strings;

class Helpers {

	public static function analyzeParams(array &$params, $controlName) {
		if (!$controlName) {
			return;
		}
		$controlName .= '-';
		foreach ($params as $name => $value) {
			if (!Strings::startsWith($name, $controlName)) {
				unset($params[$name]);
				$params[$controlName . $name] = $value;
			}
		}
	}

	public static function extractPathToArray($path, $val = NULL) {
		$arr = [];
		$pointer = &$arr;
		foreach (explode('.', $path) as $item) {
			$pointer[$item] = [];
			$pointer = &$pointer[$item];
		}
		$pointer = $val;

		return $arr;
	}

}
