<?php

namespace Webchemistry\Test\Components\Responses;

class FormResponse {

	/** @var mixed */
	private $response;

	/** @var \Nette\Forms\Form */
	private $form;

	public function __construct($response, \Nette\Forms\Form $form) {

		$this->response = $response;
		$this->form = $form;
	}

	/**
	 * @return \Nette\Forms\Form
	 */
	public function getForm() {
		return $this->form;
	}

	/**
	 * @return mixed
	 */
	public function getResponse() {
		return $this->response;
	}

}
