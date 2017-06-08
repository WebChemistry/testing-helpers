<?php

namespace WebChemistry\Testing\Components\Presenters;

use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;

class FormPresenter extends Presenter {

	/** @var Form */
	public $form;

	/** @var string */
	public $name = 'foo';

	protected function startup() {
		parent::startup();

		$this->addComponent($this->form, $this->name);
	}

	public function renderDefault() {
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/templates/control.latte');
		$template->name = $this->name;
	}

}
