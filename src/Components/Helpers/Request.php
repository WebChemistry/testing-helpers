<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Helpers;

use Nette\Http\Request as NetteRequest;

final class Request extends NetteRequest {

	public function isSameSite(): bool {
		return true;
	}

}
