<?php

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\IPresenter;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\TMagicGet;

/**
 * @property-read mixed|TextResponse $response
 * @property-read IPresenter|Presenter $presenter
 */
class PresenterResponse {

	use TMagicGet;

	/** @var mixed */
	private $response;

	/** @var IPresenter */
	private $presenter;

	public function __construct($response, IPresenter $presenter) {
		$this->response = $response;
		$this->presenter = $presenter;
	}

	/**
	 * @return mixed|TextResponse
	 */
	public function getResponse() {
		return $this->response;
	}

	/**
	 * @return IPresenter|Presenter
	 */
	public function getPresenter() {
		return $this->presenter;
	}

}
