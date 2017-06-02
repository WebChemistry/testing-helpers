<?php

namespace WebChemistry\Testing\Components\Responses;

use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;

/**
 * @property-read Form $form
 * @property-read array|ArrayHash $values
 */
class FormResponse extends BaseResponse {

	/** @var string */
	private $form;

	public function __construct(PresenterResponse $response, $form) {
		parent::__construct($response->getResponse(), $response->getPresenter());

		$this->form = $form;
	}

	/**
	 * @return FALSE|\Nette\Forms\ISubmitterControl
	 */
	public function isSubmitted() {
		return $this->getForm()->isSubmitted();
	}

	/**
	 * @return bool
	 */
	public function isSuccess() {
		return $this->getForm()->isSuccess();
	}

	/**
	 * @return bool
	 */
	public function hasErrors() {
		return $this->getForm()->hasErrors();
	}

	/**
	 * @return Form
	 */
	public function getForm() {
		return $this->presenter->getComponent($this->form);
	}

	/**
	 * @param bool $asArray
	 * @return array|ArrayHash
	 */
	public function getValues($asArray = FALSE) {
		return $this->getForm()->getValues($asArray);
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getValue($path) {
		$values = $this->getValues(TRUE);
		foreach (explode('.', $path) as $item) {
			$values = $values[$item];
		}

		return $values;
	}

}
