<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\IComponent;

class FormPresenter extends Presenter {
	public Form $form;

	public string $name = 'foo';

	/** @var callable */
	public $actionCallback;

	/** @var callable */
	public $renderCallback;

	public ?string $file;

	protected function createComponent(string $name): ?IComponent {
		if ($this->name === $name) {
			return $this->form;
		}

		return parent::createComponent($name);
	}

	public function actionDefault(): void {
		if ($cb = $this->actionCallback) {
			$cb($this->form);
		}
	}

	public function renderDefault(): void {
		if ($cb = $this->renderCallback) {
			$cb($this->form);
		}

		$template = $this->getTemplate();
		$template->setFile($this->file ?: __DIR__ . '/templates/control.latte');
		$template->name = $this->name;
	}
}
