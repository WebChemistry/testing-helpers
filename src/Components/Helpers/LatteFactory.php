<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Helpers;

use Latte;
use Nette\Bridges\ApplicationLatte\LatteFactory as ILatteFactory;
use Nette\Bridges\FormsLatte\FormsExtension;

class LatteFactory implements ILatteFactory {
	public function create(): Latte\Engine {
		$latte = new Latte\Engine();

		if (class_exists(FormsExtension::class)) {
			$latte->addExtension(new FormsExtension());
		}

		return $latte;
	}
}
