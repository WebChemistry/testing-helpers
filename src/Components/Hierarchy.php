<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components;

use WebChemistry\Testing\Components\Hierarchy\Presenter as HierarchyPresenter;

class Hierarchy {

	/** @var Presenter */
	private $presenterService;

	public function __construct() {
		$this->presenterService = new Presenter();
	}

	public function createHierarchy(string $name): HierarchyPresenter {
		return new HierarchyPresenter($name, $this->presenterService);
	}

}
