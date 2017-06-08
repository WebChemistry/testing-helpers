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

	public function __construct(Presenter $presenterService, Form $form, $name) {
		parent::__construct($presenterService, $name);

		$this->form = $form;
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

		return new FormResponse($this->createRequest($presenter), $this->name);
	}

	public function setSignal($action) {
		throw new TestException('Cannot set action in form.');
	}

}
