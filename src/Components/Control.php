<?php

namespace WebChemistry\Testing\Components;

use Nette\ComponentModel\IComponent;
use WebChemistry\Testing\Components\Requests\ControlRequest;

class Control {

	/** @var Presenter */
	private $presenter;

	/** @var callable[] */
	private $controls = [];

	public function __construct() {
		$this->presenter = new Presenter();
		$this->presenter->addMapping('*', 'WebChemistry\Testing\Components\Presenters\*Presenter');
	}

	/**
	 * @param string $name
	 * @param callable $callback
	 * @return static
	 */
	public function addControl($name, callable $callback) {
		$this->controls[$name] = $callback;

		return $this;
	}

	/**
	 * @param string $name
	 * @return IComponent
	 */
	public function createControl($name) {
		return call_user_func($this->controls[$name]);
	}

	/**
	 * @param string $name
	 * @return ControlRequest
	 */
	public function createRequest($name) {
		return new ControlRequest($this->presenter, $this->createControl($name), $name);
	}

}