<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Presenters;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\ComponentModel\IComponent;

class ControlPresenter extends Presenter {
	private bool $render = false;

	private IComponent $control;

	private string $name;

	public function setControl($name, IComponent $control): void {
		$this->control = $control;
		$this->name = $name;
	}

	public function setRender(): void {
		$this->render = true;
	}

	public function startup(): void {
		parent::startup();

		$this->control->setParent(null);
		$this->addComponent($this->control, $this->name);
	}

	public function renderDefault(): void {
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
