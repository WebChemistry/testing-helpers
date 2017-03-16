<?php

namespace WebChemistry\Testing\Components\Helpers;

use Nette\Application\UI\Presenter;

class FakePresenter extends Presenter {

	/** @var callable[] */
	private $forms = [];

	/**
	 * @param callable[] $forms
	 */
	public function setForms($forms) {
		$this->forms = $forms;
	}

	protected function startup() {
		parent::startup();

		foreach ($this->forms as $name => $callback) {
			$this->addComponent($callback(), $name);
		}
		$this->forms = [];
	}

	protected function afterRender() {
		parent::afterRender();

		$this->terminate();
	}

}
