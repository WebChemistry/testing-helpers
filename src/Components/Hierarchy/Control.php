<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\ControlResponse;
use WebChemistry\Testing\TestException;
use Nette\Application\UI;

class Control {

	/** @var PresenterRequest */
	protected $request;

	/** @var UI\Control */
	protected $control;

	public function __construct(PresenterRequest $request, UI\Control $control) {
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
		$ctrl = $this->control->getComponent($name, TRUE);
		if ($ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is form, use getForm instead of getControl.");
		}
		if (!$ctrl instanceof UI\Control) {
			throw new TestException("Component '$name' must be instance of " . UI\Control::class);
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
		Helpers::analyzeParams($params, $this->control->getUniqueId());
		$this->request->addParams($params);

		return $this;
	}

	/**
	 * @param string $signal
	 * @return ControlResponse
	 */
	public function sendSignal($signal) {
		$this->request->setSignal($this->control->getUniqueId() . '-' . $signal);

		return new ControlResponse($this->request->send(), $this->control->getUniqueId());
	}

	public function render() {
		ob_start();

		$this->request->send()->getPresenter()->getComponent($this->control->getUniqueId())->render();

		return trim(ob_get_clean());
	}

}
