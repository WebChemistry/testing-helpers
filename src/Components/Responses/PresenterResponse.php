<?php

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\IPresenter;

class PresenterResponse {

	/** @var mixed */
	private $response;

	/** @var IPresenter */
	private $presenter;

	public function __construct($response, IPresenter $presenter) {
		$this->response = $response;
		$this->presenter = $presenter;
	}

	/**
	 * @return mixed
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @return IPresenter|\Nette\Application\UI\Presenter
	 */
	public function getPresenter() {
		return $this->presenter;
	}

}
