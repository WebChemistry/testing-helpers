<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\UI;
use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class Presenter {

	/** @var Request */
	protected $request;

	/** @var IPresenter|UI\Presenter */
	protected $presenter;

	public function __construct($name, IPresenter $presenter) {
		$this->request = new Request($name, $presenter);
		$this->presenter = clone $presenter;
	}

	/**
	 * @param string $name
	 * @return Control
	 * @throws TestException
	 */
	public function getControl($name) {
		$ctrl = $this->presenter->getComponent($name);
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
		$ctrl = $this->presenter->getComponent($name);
		if (!$ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is not form, use getControl instead of getForm.");
		}

		return new Form($this->request, $ctrl);
	}

	/**
	 * @param string $action
	 * @return static
	 */
	public function setAction($action) {
		$this->request->setAction($action);

		return $this;
	}

	/**
	 * @return IPresenter|UI\Presenter
	 */
	public function getPresenter() {
		return $this->presenter;
	}

	/**
	 * @return PresenterResponse
	 */
	public function send() {
		return $this->request->sendRequest();
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->request->sendRequest()->toString();
	}

	/**
	 * @return DomQuery
	 */
	public function renderDomQuery() {
		return $this->request->sendRequest()->toDomQuery();
	}

	/**
	 * @param string $name
	 * @param array $parameters
	 * @return PresenterResponse
	 */
	public function sendSignal($name, array $parameters = []) {
		$this->request->setSignal($name);
		$this->request->addParameters($parameters);

		return $this->request->sendRequest();
	}

}
