<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\UI;
use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class Presenter {

	/** @var PresenterRequest */
	protected $request;

	/** @var IPresenter|UI\Presenter */
	protected $presenter;

	/** @var IPresenter|UI\Presenter */
	protected $requestPresenter;

	/** @var \WebChemistry\Testing\Components\Presenter */
	private $presenterService;

	/** @var string */
	private $name;

	public function __construct($name, \WebChemistry\Testing\Components\Presenter $presenterService) {
		$this->request = new PresenterRequest($presenterService, $this->requestPresenter = $presenterService->createPresenter($name), $name);
		$this->presenter = $presenterService->createPresenter($name);
		$this->presenterService = $presenterService;
		$this->name = $name;
	}

	public function cleanup() {
		$this->request = new PresenterRequest($this->presenterService, $this->requestPresenter, $this->name);
		foreach ($this->requestPresenter->getComponents() as $component) {
			$this->requestPresenter->removeComponent($component);
		}
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
		$this->request->setPresenterAction($action);

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
		return $this->request->send();
	}

	/**
	 * @return string
	 */
	public function render() {
		return $this->request->send()->toString();
	}

	/**
	 * @return DomQuery
	 */
	public function renderDomQuery() {
		return $this->request->send()->toDomQuery();
	}

	/**
	 * @param array $params
	 */
	public function addParams(array $params) {
		$this->request->addParams($params);
	}

	/**
	 * @param string $name
	 * @return PresenterResponse
	 */
	public function sendSignal($name) {
		$this->request->setSignal($name);

		return $this->request->send();
	}

}
