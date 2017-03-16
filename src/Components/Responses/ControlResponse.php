<?php

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\IPresenter;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\IComponent;
use WebChemistry\Testing\TMagicGet;

/**
 * @property-read mixed|TextResponse $response
 * @property-read IPresenter|Presenter $presenter
 * @property-read IComponent $control
 */
class ControlResponse {

	use TMagicGet;

	/** @var PresenterResponse */
	private $response;

	/** @var string */
	private $name;

	/**
	 * @param PresenterResponse $response
	 * @param string $name
	 */
	public function __construct(PresenterResponse $response, $name) {
		$this->response = $response;
		$this->name = $name;
	}

	/**
	 * @return mixed|TextResponse
	 */
	public function getResponse() {
		return $this->response->getResponse();
	}

	/**
	 * @return IPresenter|Presenter
	 */
	public function getPresenter() {
		return $this->response->getPresenter();
	}

	/**
	 * @return IComponent
	 */
	public function getControl() {
		return $this->response->getPresenter()[$this->name];
	}

}