<?php declare(strict_types = 1);

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

	/**
	 * @param PresenterResponse $response
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
	 * @param bool $asArray
	 * @return array|ArrayHash
	 */
	public function getValues(bool $asArray = true) {
		return $this->getForm()->getValues($asArray);
	}

	/**
	 * @param string $path
	 * @return mixed
	 */
	public function getValue(string $path) {
		$values = $this->getValues(true);
		foreach (explode('.', $path) as $item) {
			$values = $values[$item];
		}

		return $values;
	}

}
