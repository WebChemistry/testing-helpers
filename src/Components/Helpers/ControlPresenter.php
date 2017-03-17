<?php

namespace WebChemistry\Testing\Components\Helpers;

use Nette\Application\UI\Presenter;

class ControlPresenter extends Presenter {

	/** @var string */
	private $active;

	/** @var bool */
	private $render = FALSE;

	/** @var callable */
	private $action;

	/** @var callable[] */
	private $controls;

	public function setActionCallback(callable $action = NULL) {
		$this->action = $action;
	}

	public function setActiveControl($name) {
		$this->active = $name;
	}

	public function setRender() {
		$this->render = TRUE;
	}

	/**
	 * @param array $controls
	 */
	public function setControls(array $controls) {
		$this->controls = $controls;
	}

	protected function startup() {
		parent::startup();

		foreach ($this->controls as $name => $callback) {
			$this->addComponent($callback(), $name);
		}
		$this->controls = [];
	}

	public function actionDefault() {
		if ($callback = $this->action) {
			$callback($this[$this->active]);
		}
	}

	public function renderDefault() {
		if ($this->render) {
			$this->template->setFile(__DIR__ . '/templates/control.latte');
			$this->template->name = $this->active;
			return;
		}

		$this->terminate();
	}

}