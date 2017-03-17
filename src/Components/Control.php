<?php

namespace WebChemistry\Testing\Components;

use Nette\Application\IPresenter;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Utils\Strings;
use WebChemistry\Testing\Components\Helpers\ControlPresenter;
use WebChemistry\Testing\Components\Responses\ControlResponse;

class Control {

	/** @var Presenter */
	private $presenter;

	/** @var callable[] */
	private $controls = [];

	public function __construct() {
		$this->presenter = new Presenter();
		$this->presenter->onCreate[] = [$this, '__onCreatePresenter'];
		$this->presenter->setMapping('*', 'WebChemistry\Testing\Components\Helpers\*Presenter');
	}

	/**
	 * @param ControlPresenter $presenter
	 * @internal
	 */
	public function __onCreatePresenter(ControlPresenter $presenter) {
		$presenter->setControls($this->controls);
	}

	/**
	 * @param string $name
	 * @param callable $callback
	 */
	public function addControl($name, callable $callback) {
		$this->controls[$name] = $callback;
	}

	/**
	 * @param string $name
	 * @param array $params
	 * @param callable $actionCallback
	 * @return ControlResponse
	 */
	public function sendRequest($name, array $params = [], callable $actionCallback = NULL) {
		$presenter = $this->createPresenter();
		$presenter->setActiveControl($name);
		$presenter->setRender();
		$presenter->setActionCallback($actionCallback);

		$this->analyzeParams($params, $name);

		return new ControlResponse($this->presenter->createRequest($presenter, 'GET', $params), $name);
	}

	/**
	 * @param string $name
	 * @param array $params
	 * @param callable $actionCallback
	 * @return NULL|string null - cannot get template source
	 */
	public function renderToString($name, array $params = [], callable $actionCallback = NULL) {
		$presenter = $this->createPresenter();
		$presenter->setActiveControl($name);
		$presenter->setRender();
		$presenter->setActionCallback($actionCallback);

		$this->analyzeParams($params, $name);

		$response = $this->presenter->createRequest($presenter, 'GET', $params);

		/** @var Template $template */
		$template = $response->getResponse()->getSource();

		return trim((string) $template);
	}

	private function analyzeParams(array &$params, $controlName) {
		$controlName .= '-';
		foreach ($params as $name => $value) {
			if (!Strings::startsWith($name, $controlName)) {
				unset($params[$name]);
				$params[$controlName . $name] = $value;
			}
		}
	}

	/**
	 * @return ControlPresenter|IPresenter
	 */
	private function createPresenter() {
		return $this->presenter->createPresenter('Control');
	}

}