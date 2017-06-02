<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\UI;
use Nette\Http\FileUpload;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Responses\FormResponse;

class Form {

	/** @var Request */
	private $request;

	/** @var UI\Form */
	private $form;

	/**
	 * @param Request $request
	 * @param UI\Form $form
	 */
	public function __construct(Request $request, UI\Form $form) {
		$this->request = $request;
		$this->form = $form;
	}

	/**
	 * @param bool $includeFormName
	 * @return string
	 */
	protected function getUniqueId($includeFormName = FALSE) {
		$name = '';
		if ($includeFormName) {
			$name = $this->form->getParent()->getUniqueId() ? '-' : '';
			$name .= $this->form->getName();
		}

		return $this->form->getParent()->getUniqueId() . $name;
	}

	/**
	 * @param string $path
	 * @param FileUpload $fileUpload
	 * @return static
	 */
	public function addUpload($path, FileUpload $fileUpload) {
		$this->request->setPost($this->getUniqueId());
		$this->request->addFile(Helpers::extractPathToArray($path, $fileUpload), $fileUpload);

		return $this;
	}

	/**
	 * @param array $values
	 * @return static
	 */
	public function setValues(array $values) {
		$this->request->setPost();
		Helpers::analyzeParams($values, $this->getUniqueId());
		$this->request->addPost($values);

		return $this;
	}

	/**
	 * Sends form
	 *
	 * @return FormResponse
	 */
	public function send() {
		$this->request->setSignal($this->getUniqueId(TRUE) . '-submit');

		return new FormResponse($this->request->sendRequest(), $this->getUniqueId(TRUE));
	}

}
