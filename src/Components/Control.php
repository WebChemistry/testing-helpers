<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components;

use Nette\ComponentModel\IComponent;
use WebChemistry\Testing\Components\Requests\ControlRequest;

class Control {

	/** @var Presenter */
	private $presenter;

	public function __construct() {
		$this->presenter = new Presenter();
	}

	public function createRequest(IComponent $control, string $name = 'control'): ControlRequest {
		return new ControlRequest($this->presenter, $control, $name);
	}

}