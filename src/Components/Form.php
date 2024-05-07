<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components;

use Nette\Application\IPresenter;
use Nette\Application\UI;
use Nette\ComponentModel\IContainer;
use WebChemistry\Testing\Components\Presenters\FormPresenter;
use WebChemistry\Testing\Components\Requests\FormRequest;

class Form {
	private IPresenter|IContainer $presenter;

	private PresenterFactory $presenterFactory;

	public function __construct() {
		$this->presenterFactory = new PresenterFactory();
		$this->presenter = $this->presenterFactory->createPresenter(FormPresenter::class);
	}

	public function createRequest(UI\Form $form, string $name = 'form'): FormRequest {
		return new FormRequest($this->presenterFactory, $form, $name);
	}
}
