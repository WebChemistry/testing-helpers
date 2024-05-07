<?php declare(strict_types = 1);

namespace WebChemistry\Testing;

use WebChemistry\Testing\Components;

class Services {

	/** @var Components\PresenterFactory */
	public $presenterFactory;

	/** @var Components\Form */
	public $form;

	/** @var Components\FileSystem */
	public $fileSystem;

	/** @var Components\Control */
	public $control;

	/** @var Components\Hierarchy */
	public $hierarchy;

	public function __construct() {
		$registry = new ServiceRegistry();

		$this->presenterFactory = $registry->get('presenterService');
		$this->form = $registry->get('formService');
		$this->fileSystem = $registry->get('fileSystemService');
		$this->control = $registry->get('controlService');
		$this->hierarchy = $registry->get('hierarchyService');
	}

}
