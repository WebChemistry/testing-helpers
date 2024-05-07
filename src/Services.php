<?php

declare(strict_types=1);

namespace WebChemistry\Testing;

class Services {
	public Components\PresenterFactory $presenterFactory;

	public Components\Form $form;

	public Components\FileSystem $fileSystem;

	public Components\Control $control;

	public Components\Hierarchy $hierarchy;

	public function __construct() {
		$registry = new ServiceRegistry();

		$this->presenterFactory = $registry->get('presenterService');
		$this->form = $registry->get('formService');
		$this->fileSystem = $registry->get('fileSystemService');
		$this->control = $registry->get('controlService');
		$this->hierarchy = $registry->get('hierarchyService');
	}
}
