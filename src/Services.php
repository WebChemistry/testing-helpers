<?php

namespace WebChemistry\Test;

use Nette\Application\IPresenterFactory;
use WebChemistry\Test\Components;

class Services {

	/** @var Components\Presenter */
	private static $presenters;

	/** @var Components\Form */
	private static $forms;

	/** @var  */
	private static $fileSystem;

	public static function presenters(IPresenterFactory $presenterFactory = NULL) {
		if (!self::$presenters || $presenterFactory) {
			self::$presenters = new Components\Presenter($presenterFactory);
		}

		return self::$presenters;
	}

	public static function forms($force = FALSE) {
		if (!self::$forms || $force) {
			self::$forms = new Components\Form();
		}

		return self::$forms;
	}

	public static function fileSystem() {
		if (!self::$fileSystem) {
			self::$fileSystem = new Components\FileSystem();
		}

		return self::$fileSystem;
	}

}
