<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Requests;

use Nette\ComponentModel\IComponent;
use WebChemistry\Testing\Components\Presenters\ControlPresenter;
use WebChemistry\Testing\Components\PresenterFactory;
use WebChemistry\Testing\Components\Responses\ControlResponse;

class ControlRequest extends BaseRequest {

	/** @var IComponent */
	private $control;

	/** @var bool */
	private $render = true;

	public function __construct(PresenterFactory $presenterFactory, IComponent $control, string $name) {
		parent::__construct($presenterFactory, $name);

		$this->control = $control;
	}

	public function setRender(bool $render = true): self {
		$this->render = $render;

		return $this;
	}

	public function send(): ControlResponse {
		return new ControlResponse($this->createRequest($this->createPresenter()), $this->name);
	}

	private function createPresenter(): ControlPresenter {
		/** @var ControlPresenter $presenter */
		$presenter = $this->presenterFactory->createPresenter(ControlPresenter::class);

		$presenter->setControl($this->name, $this->control);
		if ($this->render) {
			$presenter->setRender();
		}

		return $presenter;
	}

}
