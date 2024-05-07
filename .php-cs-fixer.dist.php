<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

return (new Config())
	->setRiskyAllowed(true)
	->setRules([
		'@PHP81Migration' => true,
		'@PHP80Migration:risky' => true,
		'@Symfony' => true,
		'phpdoc_to_param_type' => true,
		'phpdoc_to_property_type' => true,
		'phpdoc_to_return_type' => true,
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
