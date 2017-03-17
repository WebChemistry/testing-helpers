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
use WebChemistry\Testing\Components\Responses;

class Presenter {

	/** @var IPresenterFactory|PresenterFactory */
	private $presenterFactory;

	/** @var callable */
	public $onCreate = [];

	public function __construct(IPresenterFactory $presenterFactory = NULL) {
		if (!$presenterFactory) {
			$presenterFactory = $this->createPresenterFactory();
		}

		$this->presenterFactory = $presenterFactory;
	}

	public function setMapping($module, $mapping) {
		$this->presenterFactory->setMapping([
			$module => $mapping
		]);
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
				$presenter->injectPrimary(NULL, NULL, NULL, $request, new Response(), NULL, NULL, $this->createTemplateFactory($request));
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

	/**
	 * @param string $name
	 * @return IPresenter|UI\Presenter
	 */
	public function createPresenter($name) {
		return $this->presenterFactory->createPresenter($name);
	}

	/**
	 * @param string|IPresenter $presenter
	 * @param string $method
	 * @param array $params
	 * @param array $post
	 * @param array $files
	 * @param array $flags
	 * @return Responses\PresenterResponse
	 */
	public function createRequest($presenter, $method = 'GET', array $params = [], array $post = [], array $files = [], array $flags = []) {
		if (!$presenter instanceof IPresenter) {
			$class = self::createPresenter($presenter);
		} else {
			$class = $presenter;
			$presenter = 'Foo';
		}
		$request = new Request($presenter, $method, $params, $post, $files, $flags);

		return new Responses\PresenterResponse($class->run($request), $class);
	}

}
