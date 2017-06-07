<?php

namespace WebChemistry\Testing\Components;

use Latte\Engine;
use Nette\Application\IPresenter;
use Nette\Application\IPresenterFactory;
use Nette\Application\PresenterFactory;
use Nette\Application\Request;
use Nette\Application\UI;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Http\IRequest;
use Nette\Http\Response;
use Nette\Http\UrlScript;
use WebChemistry\Testing\Components\Helpers\LatteFactory;
use WebChemistry\Testing\Components\Helpers\RouterStub;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses;

class Presenter {

	/** @var IPresenterFactory|PresenterFactory */
	private $presenterFactory;

	/** @var callable[] */
	public $onCreate = [];

	/** @var array */
	private $mapping = [];

	public function __construct(IPresenterFactory $presenterFactory = NULL) {
		if ($presenterFactory === NULL) {
			$presenterFactory = $this->createPresenterFactory();
		}

		$this->presenterFactory = $presenterFactory;
	}

	/**
	 * @param array $mapping
	 */
	public function setMapping(array $mapping) {
		$this->presenterFactory->setMapping($this->mapping = $mapping);
	}

	/**
	 * @param string $module
	 * @param string $mapping
	 */
	public function addMapping($module, $mapping) {
		$this->mapping[$module] = $mapping;

		$this->presenterFactory->setMapping($this->mapping);
	}

	/**
	 * @param string $name
	 * @return IPresenter|UI\Presenter
	 */
	public function createPresenter($name) {
		return $this->presenterFactory->createPresenter($name);
	}

	/**
	 * @param string|IPresenter $presenter
	 * @return PresenterRequest
	 */
	public function createRequest($presenter) {
		if (!$presenter instanceof IPresenter) {
			$class = self::createPresenter($presenter);
		} else {
			$class = $presenter;
			$presenter = 'Foo';
		}

		return new PresenterRequest($this, $class, $presenter);
	}

	/**
	 * @return PresenterFactory
	 */
	private function createPresenterFactory() {
		return new PresenterFactory(function ($class) {
			$presenter = new $class;

			if ($presenter instanceof UI\Presenter) {
				$presenter->autoCanonicalize = FALSE;

				$request = new \Nette\Http\Request(new UrlScript('http://localhost/'));
				$presenter->injectPrimary(NULL, NULL, new RouterStub(), $request, new Response(), NULL, NULL, $this->createTemplateFactory($request));
			}

			foreach ($this->onCreate as $callback) {
				$callback($presenter);
			}

			return $presenter;
		});
	}

	/**
	 * @param IRequest $request
	 * @return TemplateFactory|null
	 */
	private function createTemplateFactory(IRequest $request) {
		if (class_exists(Engine::class)) {
			return new TemplateFactory(new LatteFactory(), $request);
		}

		return NULL;
	}

}
