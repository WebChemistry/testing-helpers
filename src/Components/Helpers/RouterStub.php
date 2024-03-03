<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Helpers;

use Nette;
use Nette\Routing\Router;

class RouterStub implements Router {

	public $returnUrl = 'http://localhost/';

	public function match(Nette\Http\IRequest $httpRequest): array {
		return [];
	}

	public function constructUrl(array $params, Nette\Http\UrlScript $urlScript): string {
		return $this->returnUrl;
	}

}
