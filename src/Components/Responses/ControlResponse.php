<?php

namespace WebChemistry\Testing\Components\Responses;

use Nette\ComponentModel\IComponent;

class ControlResponse extends BaseResponse {

	/** @var string */
	private $name;

	/**
	 * @param PresenterResponse $response
	 * @param string $name
	 */
	public function __construct(PresenterResponse $response, $name) {
		parent::__construct($response->getResponse(), $response->getPresenter());

		$this->name = $name;
	}

	/**
	 * @return IComponent
	 */
	public function getControl() {
		return $this->presenter[$this->name];
	}

}