<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\UI;
use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class Presenter {

	/** @var PresenterRequest */
	protected $request;

	/** @var IPresenter|UI\Presenter */
	protected $presenter;

	/** @var \WebChemistry\Testing\Components\Presenter */
	private $presenterService;

	/** @var string */
	private $name;

	public function __construct(string $name, \WebChemistry\Testing\Components\Presenter $presenterService) {
		$this->request = new PresenterRequest($presenterService, $presenterService->createPresenter($name), $name);
		$this->presenter = $presenterService->createPresenter($name);
		$this->presenterService = $presenterService;
		$this->name = $name;
	}

	public function cleanup(): void {
		$this->request = new PresenterRequest($this->presenterService, $this->presenterService->createPresenter($this->name), $this->name);
	}

	public function getControl(string $name): Control {
		$ctrl = $this->presenter->getComponent($name);
		if ($ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is form, use getForm instead of getControl.");
		}

		return new Control($this->request, $ctrl);
	}

	public function getForm(string $name): Form {
		$ctrl = $this->presenter->getComponent($name);
		if (!$ctrl instanceof UI\Form) {
			throw new TestException("Component '$name' is not form, use getControl instead of getForm.");
		}

		return new Form($this->request, $ctrl);
	}

	public function setAction(string $action): self {
		$this->request->setPresenterAction($action);

		return $this;
	}

	/**
	 * @return IPresenter|UI\Presenter
	 */
	public function getPresenter(): IPresenter {
		return $this->presenter;
	}

	/**
	 * @return PresenterResponse
	 */
	public function send(): PresenterResponse {
		return $this->request->send();
	}

	/**
	 * @return string
	 */
	public function render(): string {
		return $this->request->send()->toString();
	}

	/**
	 * @return DomQuery
	 */
	public function toDomQuery(): DomQuery {
		return $this->request->send()->toDomQuery();
	}

	public function addParams(array $params): void {
		$this->request->addParams($params);
	}

	public function sendSignal(string $name): PresenterResponse {
		$this->request->setSignal($name);

		return $this->request->send();
	}

}
