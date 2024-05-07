<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\IPresenter;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\Components\Hierarchy\DomQuery;

abstract class BaseResponse {
	public $response;

	/** @var IPresenter|Presenter */
	public $presenter;

	public function __construct($response, IPresenter $presenter) {
		$this->response = $response;
		$this->presenter = $presenter;
	}

	public function getResponse() {
		return $this->response;
	}

	public function getPresenter(): IPresenter|Presenter {
		return $this->presenter;
	}

	public function toString(): string {
		$source = $this->response->getSource();

		return (string) $source;
	}

	public function toDomQuery(): DomQuery {
		return DomQuery::fromHtml($this->toString());
	}
}
