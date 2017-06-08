<?php

namespace WebChemistry\Testing\Components\Presenters;

use Nette\Application\UI\Presenter;

class FormPresenter extends Presenter {

	/** @var array */
	public $startupComponents = [];

	protected function startup() {
		parent::startup();

		foreach ($this->startupComponents as $name => $component) {
			$this->addComponent($component, $name);
		}
	}

	protected function afterRender() {
		parent::afterRender();

		$this->terminate();
	}

}
