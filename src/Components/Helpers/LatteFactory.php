<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Helpers;

use Latte;
use Nette\Bridges\ApplicationLatte\LatteFactory as ILatteFactory;

class LatteFactory implements ILatteFactory {
	public function create(): Latte\Engine {
		return new Latte\Engine();
	}
}
