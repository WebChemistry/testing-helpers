<?php

namespace WebChemistry\Testing\Components\Requests;

use Nette\ComponentModel\IComponent;
use WebChemistry\Testing\Components\Presenters\ControlPresenter;
use WebChemistry\Testing\Components\Presenter;
use WebChemistry\Testing\Components\Responses\ControlResponse;

class ControlRequest extends BaseRequest {

	const CONTROL_PRESENTER = 'Control';

	/** @var IComponent */
	private $control;

	/** @var bool */
	private $render = FALSE;

	public function __construct(Presenter $presenterService, IComponent $control, $name) {
		parent::__construct($presenterService, $name);

		$this->control = $control;
	}

	public function setRender($render = TRUE) {
		$this->render = $render;

		return $this;
	}

	public function send() {
		return new ControlResponse($this->createRequest($this->createPresenter()), $this->name);
	}

	private function createPresenter() {
		/** @var ControlPresenter $presenter */
		$presenter = $this->presenterService->createPresenter(self::CONTROL_PRESENTER);

		$presenter->setControl($this->name, $this->control);
		if ($this->render) {
			$presenter->setRender();
		}

		return $presenter;
	}

}
