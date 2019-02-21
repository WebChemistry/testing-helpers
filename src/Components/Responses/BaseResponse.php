<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\IPresenter;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\Components\Hierarchy\DomQuery;

abstract class BaseResponse {

	/** @var mixed */
	public $response;

	/** @var IPresenter|Presenter */
	public $presenter;

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
	public function toString(): string {
		$source = $this->response->getSource();

		return (string) $source;
	}

	public function toDomQuery(): DomQuery {
		return DomQuery::fromHtml($this->toString());
	}

}
