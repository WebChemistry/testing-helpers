<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components;

use Latte\Engine;
use Nette\Application\IPresenter;
use Nette\Application\IPresenterFactory;
use Nette\Application\UI;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Http\IRequest;
use Nette\Http\Response;
use Nette\Http\UrlScript;
use WebChemistry\Testing\Components\Helpers\LatteFactory;
use WebChemistry\Testing\Components\Helpers\Request;
use WebChemistry\Testing\Components\Helpers\RouterStub;
use WebChemistry\Testing\Components\Requests\PresenterRequest;

class PresenterFactory implements IPresenterFactory {
	/** @var callable[] */
	public array $onCreate = [];

	/**
	 * Generates and checks presenter class name.
	 *
	 * @throws InvalidPresenterException
	 */
	public function getPresenterClass(string &$name): string {
		if (!class_exists($name)) {
			throw new InvalidPresenterException("Cannot load presenter '$class', class '$class' was not found.");
		}

		return $name;
	}

	/**
	 * @return IPresenter|UI\Presenter
	 */
	public function createPresenter(string $class): IPresenter {
		$presenter = new $class();

		if ($presenter instanceof UI\Presenter) {
			$presenter->autoCanonicalize = false;

			$request = new Request(new UrlScript('http://localhost/'));
			$presenter->injectPrimary(
				$request,
				new Response(),
				$this,
				new RouterStub(),
				null,
				null,
				$this->createTemplateFactory($request)
			);
		}

		foreach ($this->onCreate as $callback) {
			$callback($presenter);
		}

		return $presenter;
	}

	public function createRequest(string|IPresenter $presenter): PresenterRequest {
		if (!$presenter instanceof IPresenter) {
			$class = $this->createPresenter($presenter);
		} else {
			$class = $presenter;
			$presenter = 'Foo';
		}

		return new PresenterRequest($this, $class, $presenter);
	}

	private function createTemplateFactory(IRequest $request): ?TemplateFactory {
		if (class_exists(Engine::class)) {
			return new TemplateFactory(new LatteFactory(), $request);
		}

		return null;
	}
}
