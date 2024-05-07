<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\UI;
use Nette\ComponentModel\Container;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\ControlResponse;
use WebChemistry\Testing\TestException;

class Control {
	protected PresenterRequest $request;

	protected Container $control;

	public function __construct(PresenterRequest $request, Container $control) {
		$this->request = $request;
		$this->control = $control;
	}

	public function getObject(): Container {
		return $this->control;
	}

	/**
	 * @throws TestException
	 */
	public function getControl(string $name): Control {
		$ctrl = $this->control->getComponent($name, true);
		if ($ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is form, use getForm instead of getControl.");
		}
		if (!$ctrl instanceof Container) {
			throw new TestException("Component '$name' must be instance of Nette\\ComponentModel\\Container");
		}

		return new Control($this->request, $ctrl);
	}

	/**
	 * @throws TestException
	 */
	public function getForm(string $name): Form {
		$ctrl = $this->control->getComponent($name);
		if (!$ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is not form, use getControl instead of getForm.");
		}

		return new Form($this->request, $ctrl);
	}

	public function addParams(array $params): self {
		Helpers::analyzeParams($params, $this->control->lookupPath('Nette\Application\IPresenter'));
		$this->request->addParams($params);

		return $this;
	}

	public function sendSignal(string $signal): ControlResponse {
		$this->request->setSignal($this->control->lookupPath('Nette\Application\IPresenter') . '-' . $signal);

		return new ControlResponse($this->request->send(), $this->control->lookupPath('Nette\Application\IPresenter'));
	}

	public function render(): string {
		ob_start();

		$this->request->send()->getPresenter()->getComponent($this->control->lookupPath('Nette\Application\IPresenter'))->render();

		return trim(ob_get_clean());
	}
}
