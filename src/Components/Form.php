<?php

namespace WebChemistry\Test\Components;

use WebChemistry\Test\Components\Helpers\FakePresenter;
use WebChemistry\Test\TestException;
use WebChemistry\Test\Components\Responses;

class Form {

	/** @var callable[] */
	private $forms;

	public function __construct() {
		$this->presenters = new Presenter();
		$this->presenters->onCreate[] = [$this, '__onCreatePresenter'];
		$this->presenters->setMapping('*', 'WebChemistry\Test\Components\Helpers\*Presenter');
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

	public function createRequest($name, array $post = [], array $files = []) {
		if (!isset($this->forms[$name])) {
			throw new TestException("Form '$name' not exists.");
		}

		$response = $this->presenters->createRequest('Fake', 'POST', [
			'do' => $name . '-submit'
		], $post, $files);
		$presenter = $response->getPresenter();

		return new Responses\FormResponse($response->getResponse(), $presenter->getComponent($name));
	}

}
