<?php

namespace WebChemistry\Testing\Components;

use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Builders\FormSender;

class Form {

	/** @var callable[] */
	private $forms;

	/** @var IPresenter */
	private $presenter;

	/** @var array */
	private $counter = [];

	/** @var Presenter */
	private $presenters;

	public function __construct() {
		$this->presenters = new Presenter();
		$this->presenters->setMapping('*', 'WebChemistry\Testing\Components\Helpers\*Presenter');

		$this->presenter = $this->presenters->createPresenter('Fake');
	}

	/**
	 * @param string $name
	 * @param callable
	 */
	public function addForm($name, callable $form) {
		$this->forms[$name] = $form;
		$this->counter[$name] = 1;
	}

	/**
	 * @param string $name
	 * @return \Nette\Application\UI\Form
	 */
	public function createForm($name) {
		$args = func_get_args(); array_shift($args);
		$form = call_user_func_array($this->forms[$name], $args);
		$this->presenter->addComponent($form, $name . $this->counter[$name]++);

		return $form;
	}

	/**
	 * @param string $name
	 * @param ... $params
	 * @return \Nette\Application\UI\Form
	 */
	public function createPureForm($name) {
		$args = func_get_args(); array_shift($args);

		return call_user_func_array($this->forms[$name], $args);
	}

	/**
	 * @param string $name
	 * @param ... $params
	 * @return FormSender
	 */
	public function createSender($name) {
		$args = func_get_args(); array_shift($args);
		$form = call_user_func_array($this->forms[$name], $args);

		return new FormSender($form, $this->presenters, $name);
	}

}
