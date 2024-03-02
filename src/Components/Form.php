<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components;

use Nette\Application\IPresenter;
use Nette\ComponentModel\IContainer;
use WebChemistry\Testing\Components\Presenters\FormPresenter;
use WebChemistry\Testing\Components\Requests\FormRequest;
use Nette\Application\UI;

class Form {

	/** @var IPresenter|IContainer */
	private $presenter;

	/** @var Presenter */
	private $presenters;

	public function __construct() {
		$this->presenters = new Presenter();
		$this->presenter = $this->presenters->createPresenter(FormPresenter::class);
	}

	public function createRequest(UI\Form $form, string $name = 'form'): FormRequest {
		return new FormRequest($this->presenters, $form, $name);
	}

}
