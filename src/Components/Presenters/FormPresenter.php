<?php

namespace WebChemistry\Testing\Components\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class FormPresenter extends Presenter {

	/** @var Form */
	public $form;

	/** @var string */
	public $name = 'foo';

	/** @var callable */
	public $actionCallback;

	/** @var callable */
	public $renderCallback;

	/** @var string|null */
	public $file;

	protected function createComponent($name) {
		if ($this->name === $name) {
			return $this->form;
		}

		return parent::createComponent($name);
	}

	public function actionDefault() {
		if ($cb = $this->actionCallback) {
			$cb($this->form);
		}
	}

	public function renderDefault() {
		if ($cb = $this->renderCallback) {
			$cb($this->form);
		}

		$template = $this->getTemplate();
		$template->setFile($this->file ? : __DIR__ . '/templates/control.latte');
		$template->name = $this->name;
	}

}
