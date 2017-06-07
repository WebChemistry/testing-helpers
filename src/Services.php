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
 * @property-read Components\Hierarchy $hierarchy
 */
class Services {

	use TMagicGet;

	/** @var Components\Presenter */
	private $presenter;

	/** @var Components\Form */
	private $form;

	/** @var Components\FileSystem */
	private $fileSystem;

	/** @var Components\Control */
	private $control;

	/** @var Components\Hierarchy */
	private $hierarchy;

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
	 * @return Components\Form
	 */
	public function getForm() {
		if (!$this->form) {
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

	public function getHierarchy() {
		if (!$this->hierarchy) {
			$this->hierarchy = new Components\Hierarchy();
		}

		return $this->hierarchy;
	}

}
