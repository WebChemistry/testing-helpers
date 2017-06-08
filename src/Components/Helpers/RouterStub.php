<?php

namespace WebChemistry\Testing\Components\Helpers;

use Nette;
use Nette\Application\IRouter;
use Nette\Application\Request;

class RouterStub implements IRouter {

	public $returnUrl = 'http://localhost/';

	public function match(Nette\Http\IRequest $httpRequest) {
		return $httpRequest;
	}

	public function constructUrl(Request $appRequest, Nette\Http\Url $refUrl) {
		return $this->returnUrl;
	}

}
