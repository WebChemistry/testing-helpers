<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
	->setRiskyAllowed(true)
	->setRules([
		'@Symfony' => true,
		// Override some Symfony rules.
		'braces_position' => [
			'functions_opening_brace' => 'same_line',
			'classes_opening_brace' => 'same_line',
		],
		'concat_space' => ['spacing' => 'one'],
		'no_null_property_initialization' => false,
		'yoda_style' => false,
	])
		->setIndent("\t")
	->setFinder(
		(new Finder())
			->ignoreDotFiles(false)
			->ignoreVCSIgnored(true)
			->in(__DIR__)
	)
;
