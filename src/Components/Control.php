<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components;

use Nette\ComponentModel\IComponent;
use WebChemistry\Testing\Components\Requests\ControlRequest;

class Control {

	/** @var PresenterFactory */
	private $presenterFactory;

	public function __construct() {
		$this->presenterFactory = new PresenterFactory();
	}

	public function createRequest(IComponent $control, string $name = 'control'): ControlRequest {
		return new ControlRequest($this->presenterFactory, $control, $name);
	}

}
