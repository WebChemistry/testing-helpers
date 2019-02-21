<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\IPresenter;
use Nette\Application\UI;
use Nette\ComponentModel\Component;
use Nette\Http\FileUpload;
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

	protected function getUniqueId(bool $parent = false): string {
		if ($parent) {
			$ctrl = $this->form->getParent();
			if ($ctrl instanceof Component && !$ctrl instanceof IPresenter) {
				return $ctrl->lookupPath(UI\Presenter::class, true);
			}
		} else {
			return $this->form->lookupPath(UI\Presenter::class, true);
		}

		return '';
	}

	public function addFileUpload(string $name, FileUpload $fileUpload) {
		$this->request->addFileUpload($name, $fileUpload);
	}

	public function addUpload(string $name, string $filePath): self {
		$this->request->addFile($name, $filePath);

		return $this;
	}

	public function setValues(array $values): self {
		$this->request->addPosts($values);

		return $this;
	}

	public function send(): FormResponse {
		$this->request->setSignal($this->getUniqueId() . '-submit');
		$this->request->setMethod('POST');

		return new FormResponse($this->request->send(), $this->getUniqueId());
	}

}
