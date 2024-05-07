<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\UI\Form;
use Nette\Forms\Container;
use Nette\Forms\Control;

class FormResponse extends BaseResponse {
	/** @var string */
	private $form;

	/**
	 * @param string $form
	 */
	public function __construct(PresenterResponse $response, $form) {
		parent::__construct($response->getResponse(), $response->getPresenter());

		$this->form = $form;
	}

	/**
	 * @return false|\Nette\Forms\ISubmitterControl
	 */
	public function isSubmitted() {
		return $this->getForm()->isSubmitted();
	}

	public function isSuccess(): bool {
		return $this->getForm()->isSuccess();
	}

	public function hasErrors(): bool {
		return $this->getForm()->hasErrors();
	}

	public function getForm(): Form {
		return $this->presenter->getComponent($this->form);
	}

	/**
	 * Returns the values submitted by the form.
	 *
	 * @param Control[]|null $controls
	 */
	public function getValues(string|object $returnType = Container::Array, ?array $controls = null): object|array {
		return $this->getForm()->getValues($returnType, $controls);
	}

	public function getValue(string $path) {
		$values = $this->getValues(Container::Array);
		foreach (explode('.', $path) as $item) {
			$values = $values[$item];
		}

		return $values;
	}
}
