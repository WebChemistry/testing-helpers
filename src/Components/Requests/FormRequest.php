<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Requests;

use Nette\Application\UI\Form;
use WebChemistry\Testing\Components\PresenterFactory;
use WebChemistry\Testing\Components\Presenters\FormPresenter;
use WebChemistry\Testing\Components\Responses\FormResponse;
use WebChemistry\Testing\TestException;

class FormRequest extends BaseRequest {
	/** @var Form */
	private $form;

	/** @var callable */
	private $actionCallback;

	/** @var callable */
	private $renderCallback;

	public function __construct(PresenterFactory $presenterFactory, Form $form, $name) {
		parent::__construct($presenterFactory, $name);

		$this->form = $form;
	}

	public function modifyForm(callable $callback): self {
		$callback($this->form);

		return $this;
	}

	public function setActionCallback(callable $callback): self {
		$this->actionCallback = $callback;

		return $this;
	}

	public function setRenderCallback(callable $callback): self {
		$this->renderCallback = $callback;

		return $this;
	}

	public function send(): FormResponse {
		$this->signal = $this->name . '-submit';
		$this->setMethod('POST');

		return $this->render();
	}

	public function render(?string $templateFile = null): FormResponse {
		/** @var FormPresenter $presenter */
		$presenter = $this->presenterFactory->createPresenter(FormPresenter::class);
		$presenter->name = $this->name;
		$presenter->form = $this->form;
		$presenter->file = $templateFile;
		$presenter->actionCallback = $this->actionCallback;
		$presenter->renderCallback = $this->renderCallback;

		return new FormResponse($this->createRequest($presenter), $this->name);
	}

	public function setSignal($action) {
		throw new TestException('Cannot set action in form.');
	}
}
