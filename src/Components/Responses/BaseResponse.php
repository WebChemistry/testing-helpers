<?php

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\IPresenter;
use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\Components\Hierarchy\DomQuery;
use WebChemistry\Testing\TMagicGet;

/**
 * @property-read mixed|TextResponse $response
 * @property-read IPresenter|Presenter $presenter
 */
abstract class BaseResponse {

	use TMagicGet;

	/** @var mixed */
	protected $response;

	/** @var IPresenter|Presenter */
	protected $presenter;

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
	 * @return IPresenter|Presenter
	 */
	public function getPresenter() {
		return $this->presenter;
	}

	/**
	 * @return string
	 */
	public function toString() {
		$source = $this->response->getSource();

		return (string) $source;
	}

	/**
	 * @return DomQuery
	 */
	public function toDomQuery() {
		return DomQuery::fromHtml($this->toString());
	}

}
