<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\ControlResponse;
use WebChemistry\Testing\TestException;
use Nette\ComponentModel\Container;
use Nette\Application\UI;

class Control {

	/** @var PresenterRequest */
	protected $request;

	/** @var Container */
	protected $control;

	public function __construct(PresenterRequest $request, Container $control) {
		$this->request = $request;
		$this->control = $control;
	}

	/**
	 * @return Container
	 */
	public function getObject() {
		return $this->control;
	}

	/**
	 * @param string $name
	 * @return Control
	 * @throws TestException
	 */
	public function getControl($name) {
		$ctrl = $this->control->getComponent($name, TRUE);
		if ($ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is form, use getForm instead of getControl.");
		}
		if (!$ctrl instanceof Container) {
			throw new TestException("Component '$name' must be instance of Nette\\ComponentModel\\Container");
		}

		return new Control($this->request, $ctrl);
	}

	/**
	 * @param string $name
	 * @return Form
	 * @throws TestException
	 */
	public function getForm($name) {
		$ctrl = $this->control->getComponent($name);
		if (!$ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is not form, use getControl instead of getForm.");
		}

		return new Form($this->request, $ctrl);
	}

	/**
	 * @param array $params
	 * @return static
	 */
	public function addParams(array $params) {
		Helpers::analyzeParams($params, $this->control->lookupPath('Nette\Application\IPresenter'));
		$this->request->addParams($params);

		return $this;
	}

	/**
	 * @param string $signal
	 * @return ControlResponse
	 */
	public function sendSignal($signal) {
		$this->request->setSignal($this->control->lookupPath('Nette\Application\IPresenter') . '-' . $signal);

		return new ControlResponse($this->request->send(), $this->control->lookupPath('Nette\Application\IPresenter'));
	}

	public function render() {
		ob_start();

		$this->request->send()->getPresenter()->getComponent($this->control->lookupPath('Nette\Application\IPresenter'))->render();

		return trim(ob_get_clean());
	}

}
