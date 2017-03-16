<?php

namespace WebChemistry\Testing\Components\Helpers;

use Latte;
use Nette\Bridges\ApplicationLatte\ILatteFactory;

class LatteFactory implements ILatteFactory {

	public function create() {
		return new Latte\Engine();
	}

}