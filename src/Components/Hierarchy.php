<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components;

use WebChemistry\Testing\Components\Hierarchy\Presenter as HierarchyPresenter;

class Hierarchy {

	/** @var PresenterFactory */
	private $presenterFactory;

	public function __construct() {
		$this->presenterFactory = new PresenterFactory();
	}

	public function createHierarchy(string $name): HierarchyPresenter {
		return new HierarchyPresenter($name, $this->presenterFactory);
	}

}
