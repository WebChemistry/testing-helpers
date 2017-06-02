<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Responses\ControlResponse;
use WebChemistry\Testing\TestException;
use Nette\Application\UI;

class Control {

	/** @var Request */
	protected $request;

	/** @var UI\Control */
	protected $control;

	public function __construct(Request $request, UI\Control $control) {
		$this->request = $request;
		$this->control = $control;
	}

	/**
	 * @return UI\Control
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
		$ctrl = $this->control->getComponent($name);
		if ($ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is form, use getForm instead of getControl.");
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
	public function setParameters(array $params) {
		Helpers::analyzeParams($params, $this->control->getUniqueId());

		$this->request->addParameters($params);

		return $this;
	}

	/**
	 * @param string $signal
	 * @param array $params
	 * @return ControlResponse
	 */
	public function sendSignal($signal, array $params = []) {
		Helpers::analyzeParams($params, $this->control->getUniqueId());

		$this->request->setSignal($this->control->getUniqueId() . '-' . $signal);
		$this->request->addParameters($params);

		return new ControlResponse($this->request->sendRequest(), $this->control->getUniqueId());
	}

	public function render() {
		ob_start();

		$this->request->sendRequest()->getPresenter()[$this->control->getUniqueId()]->render();

		return trim(ob_get_clean());
	}

}
