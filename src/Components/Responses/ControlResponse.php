<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Responses;

use Nette\ComponentModel\IComponent;

class ControlResponse extends BaseResponse {
	private string $name;

	public function __construct(PresenterResponse $response, string $name) {
		parent::__construct($response->getResponse(), $response->getPresenter());

		$this->name = $name;
	}

	public function getControl(): IComponent {
		return $this->presenter->getComponent($this->name);
	}
}
