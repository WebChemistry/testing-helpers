<?php

namespace WebChemistry\Testing\Components\Requests;

use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Presenter;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class PresenterRequest extends BaseRequest {

	/** @var IPresenter */
	private $presenter;

	/** @var string */
	private $presenterAction;

	public function __construct(Presenter $presenterService, IPresenter $presenter, $name) {
		parent::__construct($presenterService, $name);

		$this->presenter = $presenter;
	}

	public function setControlParams(array $params) {
		throw new TestException('Cannot setControlParams in presenter.');
	}

	/**
	 * @param string $presenterAction
	 * @return static
	 */
	public function setPresenterAction($presenterAction) {
		$this->presenterAction = $presenterAction;

		return $this;
	}

	public function send() {
		if ($this->presenterAction) {
			$this->params['action'] = $this->presenterAction;
		}
		$request = $this->createApplicationRequest();

		return new PresenterResponse($this->presenter->run($request), $this->presenter);
	}

}
