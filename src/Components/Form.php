<?php

namespace WebChemistry\Testing\Components;

use WebChemistry\Testing\Components\Helpers\FakePresenter;
use WebChemistry\Testing\TestException;
use WebChemistry\Testing\Components\Responses;

class Form {

	/** @var callable[] */
	private $forms;

	public function __construct() {
		$this->presenters = new Presenter();
		$this->presenters->onCreate[] = [$this, '__onCreatePresenter'];
		$this->presenters->setMapping('*', 'WebChemistry\Testing\Components\Helpers\*Presenter');
	}

	/**
	 * @param FakePresenter $presenter
	 * @internal
	 */
	public function __onCreatePresenter(FakePresenter $presenter) {
		$presenter->setForms($this->forms);
	}

	/**
	 * @param string $name
	 * @param callable
	 */
	public function addForm($name, callable $form) {
		$this->forms[$name] = $form;
	}

	/**
	 * @param string $name
	 * @return \Nette\Application\UI\Form
	 */
	public function getForm($name) {
		return $this->forms[$name]();
	}

	/**
	 * Sends form
	 *
	 * @deprecated
	 * @param string $name
	 * @param array $post
	 * @param array $files
	 * @return Responses\FormResponse
	 */
	public function createRequest($name, array $post = [], array $files) {
		return $this->send($name, $post, $files);
	}

	/**
	 * Sends form
	 *
	 * @param string $name
	 * @param array $post
	 * @param array $files
	 * @return Responses\FormResponse
	 * @throws TestException
	 */
	public function send($name, array $post = [], array $files = [], callable $actionCallback = NULL) {
		if (!isset($this->forms[$name])) {
			throw new TestException("Form '$name' not exists.");
		}

		/** @var FakePresenter $presenter */
		$presenter = $this->presenters->createPresenter('Fake');
		$presenter->setActive($name);
		$presenter->setActionCallback($actionCallback);

		$response = $this->presenters->createRequest($presenter, 'POST', [
			'do' => $name . '-submit'
		], $post, $files);

		return new Responses\FormResponse($response, $name);
	}

}
