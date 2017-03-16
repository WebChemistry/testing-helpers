<?php

namespace WebChemistry\Testing\Components\Helpers;

use Nette\Application\UI\Presenter;

class FakePresenter extends Presenter {

	/** @var callable[] */
	private $forms = [];

	/** @var callable */
	private $action;

	/** @var string */
	private $active;

	public function setActive($name) {
		$this->active = $name;
	}

	/**
	 * @param callable|NULL $action
	 */
	public function setActionCallback(callable $action = NULL) {
		$this->action = $action;
	}

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

	public function actionDefault() {
		if ($callback = $this->action) {
			$callback($this[$this->active]);
		}
	}

	protected function afterRender() {
		parent::afterRender();

		$this->terminate();
	}

}
