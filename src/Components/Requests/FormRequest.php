<?php

namespace WebChemistry\Testing\Components\Requests;

use Nette\Application\UI\Form;
use WebChemistry\Testing\Components\Presenters\FormPresenter;
use WebChemistry\Testing\Components\Presenter;
use WebChemistry\Testing\Components\Responses\FormResponse;
use WebChemistry\Testing\TestException;

class FormRequest extends BaseRequest {

	/** @var Form */
	private $form;

	/** @var callable */
	private $actionCallback;

	/** @var callable */
	private $renderCallback;

	public function __construct(Presenter $presenterService, Form $form, $name) {
		parent::__construct($presenterService, $name);

		$this->form = $form;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setActionCallback(callable $callback) {
		$this->actionCallback = $callback;

		return $this;
	}

	/**
	 * @param callable $callback
	 * @return static
	 */
	public function setRenderCallback(callable $callback) {
		$this->renderCallback = $callback;

		return $this;
	}

	/**
	 * @return FormResponse
	 */
	public function send() {
		$this->signal = $this->name . '-submit';
		$this->setMethod('POST');

		return $this->render();
	}

	/**
	 * @return FormResponse
	 */
	public function render() {
		/** @var FormPresenter $presenter */
		$presenter = $this->presenterService->createPresenter('Form');
		$presenter->name = $this->name;
		$presenter->form = $this->form;
		$presenter->actionCallback = $this->actionCallback;
		$presenter->renderCallback = $this->renderCallback;

		return new FormResponse($this->createRequest($presenter), $this->name);
	}

	public function setSignal($action) {
		throw new TestException('Cannot set action in form.');
	}

}
