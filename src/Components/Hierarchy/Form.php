<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\IPresenter;
use Nette\Application\UI;
use Nette\ComponentModel\Component;
use Nette\Http\FileUpload;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\FormResponse;

class Form {

	/** @var PresenterRequest */
	private $request;

	/** @var UI\Form */
	private $form;

	/**
	 * @param PresenterRequest $request
	 * @param UI\Form $form
	 */
	public function __construct(PresenterRequest $request, UI\Form $form) {
		$this->request = $request;
		$this->form = $form;
	}

	/**
	 * @param bool $parent
	 * @return string
	 */
	protected function getUniqueId($parent = FALSe) {
		if ($parent) {
			$ctrl = $this->form->getParent();
			if ($ctrl instanceof Component && !$ctrl instanceof IPresenter) {
				return $ctrl->lookupPath(UI\Presenter::class, TRUE);
			}
		} else {
			return $this->form->lookupPath(UI\Presenter::class, TRUE);
		}

		return '';
	}

	/**
	 * @param string $name
	 * @param FileUpload $fileUpload
	 */
	public function addFileUpload($name, FileUpload $fileUpload) {
		$this->request->addFileUpload($name, $fileUpload);
	}

	/**
	 * @param string $name
	 * @param string $filePath
	 * @return static
	 */
	public function addUpload($name, $filePath) {
		$this->request->addFile($name, $filePath);

		return $this;
	}

	/**
	 * @param array $values
	 * @return static
	 */
	public function setValues(array $values) {
		$this->request->addPosts($values);

		return $this;
	}

	/**
	 * Sends form
	 *
	 * @return FormResponse
	 */
	public function send() {
		$this->request->setSignal($this->getUniqueId() . '-submit');
		$this->request->setMethod('POST');

		return new FormResponse($this->request->send(), $this->getUniqueId());
	}

}
