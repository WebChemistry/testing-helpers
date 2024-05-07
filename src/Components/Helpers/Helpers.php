<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Helpers;

class Helpers {
	public static function analyzeParams(array &$params, string $controlName): void {
		if (!$controlName) {
			return;
		}
		$controlName .= '-';
		foreach ($params as $name => $value) {
			if (!str_starts_with($name, $controlName)) {
				unset($params[$name]);
				$params[$controlName . $name] = $value;
			}
		}
	}
}
