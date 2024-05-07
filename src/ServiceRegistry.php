<?php

declare(strict_types=1);

namespace WebChemistry\Testing;

use WebChemistry\Testing\Components\Control;
use WebChemistry\Testing\Components\FileSystem;
use WebChemistry\Testing\Components\Form;
use WebChemistry\Testing\Components\Hierarchy;
use WebChemistry\Testing\Components\PresenterFactory;

final class ServiceRegistry {
	private array $registry = [];

	private array $meta = [];

	public function __construct() {
		$this->meta = [
			'presenterService' => PresenterFactory::class,
			'formService' => Form::class,
			'fileSystemService' => FileSystem::class,
			'hierarchyService' => Hierarchy::class,
			'controlService' => Control::class,
		];
	}

	public function get(string $name, ...$params) {
		if (!isset($this->registry[$name])) {
			$this->registry[$name] = $this->create($name, ...$params);
		}

		return $this->registry[$name];
	}

	public function create(string $name, ...$params) {
		$obj = new $this->meta[$name](...$params);
		if (method_exists($obj, 'setRegistry')) {
			$obj->setRegistry($obj);
		}

		return $obj;
	}
}
