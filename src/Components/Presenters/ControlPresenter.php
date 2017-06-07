<?php

namespace WebChemistry\Testing\Components\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;

class ControlPresenter extends Presenter {

	/** @var bool */
	private $render = FALSE;

	/** @var IComponent */
	private $control;

	/** @var string */
	private $name;

	public function setControl($name, IComponent $control) {
		$this->control = $control;
		$this->name = $name;
	}

	public function setRender() {
		$this->render = TRUE;
	}

	public function startup() {
		parent::startup();

		$this->control->setParent();
		$this->addComponent($this->control, $this->name);
	}

	public function renderDefault() {
		if ($this->render) {
			/** @var Template|\stdClass $template */
			$template = $this->getTemplate();
			$template->setFile(__DIR__ . '/templates/control.latte');
			$template->name = $this->name;
			return;
		}

		$this->terminate();
	}

}