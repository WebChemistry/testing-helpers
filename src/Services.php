<?php

namespace WebChemistry\Testing;

use Nette\Application\IPresenterFactory;
use Nette\Utils\ObjectMixin;
use WebChemistry\Testing\Components;

/**
 * @property-read Components\Form $form
 * @property-read Components\FileSystem $fileSystem
 * @property-read Components\Presenter $presenter
 * @property-read Components\Control $control
 */
class Services {

	/** @var Components\Presenter */
	private $presenter;

	/** @var Components\Form */
	private $form;

	/** @var Components\FileSystem */
	private $fileSystem;

	/** @var Components\Control */
	private $control;

	/**
	 * @param IPresenterFactory|NULL $presenterFactory
	 * @return Components\Presenter
	 */
	public function getPresenter(IPresenterFactory $presenterFactory = NULL) {
		if (!$this->presenter || $presenterFactory) {
			$this->presenter = new Components\Presenter($presenterFactory);
		}

		return $this->presenter;
	}

	/**
	 * @param bool $force
	 * @return Components\Form
	 */
	public function getForm($force = FALSE) {
		if (!$this->form || $force) {
			$this->form = new Components\Form();
		}

		return $this->form;
	}

	/**
	 * @return Components\FileSystem
	 */
	public function getFileSystem() {
		if (!$this->fileSystem) {
			$this->fileSystem = new Components\FileSystem();
		}

		return $this->fileSystem;
	}

	/**
	 * @return Components\Control
	 */
	public function getControl() {
		if (!$this->control) {
			$this->control = new Components\Control();
		}

		return $this->control;
	}

	public function __get($name) {
		return ObjectMixin::get($this, $name);
	}

}
