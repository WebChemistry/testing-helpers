<?php

namespace WebChemistry\Testing\Components;

use Nette\Application\IPresenter;
use Nette\ComponentModel\IContainer;
use WebChemistry\Testing\Components\Requests\FormRequest;

class Form {

	/** @var callable[] */
	private $forms;

	/** @var IPresenter|IContainer */
	private $presenter;

	/** @var array */
	private $counter = [];

	/** @var Presenter */
	private $presenters;

	public function __construct() {
		$this->presenters = new Presenter();
		$this->presenters->addMapping('*', 'WebChemistry\Testing\Components\Presenters\*Presenter');

		$this->presenter = $this->presenters->createPresenter('Form');
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
	 * @return FormRequest
	 */
	public function createRequest($name) {
		return new FormRequest($this->presenters, call_user_func_array([$this, 'createPureForm'], func_get_args()), $name);
	}

}
