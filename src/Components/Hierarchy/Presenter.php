<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\IPresenter;
use Nette\Application\UI;
use WebChemistry\Testing\Components\PresenterFactory;
use WebChemistry\Testing\Components\Requests\PresenterRequest;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class Presenter {
	/** @var PresenterRequest */
	protected $request;

	/** @var IPresenter|UI\Presenter */
	protected $presenter;

	/** @var PresenterFactory */
	private $presenterFactory;

	/** @var string */
	private $name;

	public function __construct(string $name, PresenterFactory $presenterFactory) {
		$this->request = new PresenterRequest($presenterFactory, $presenterFactory->createPresenter($name), $name);
		$this->presenter = $presenterFactory->createPresenter($name);
		$this->presenterFactory = $presenterFactory;
		$this->name = $name;
	}

	public function cleanup(): void {
		$this->request = new PresenterRequest($this->presenterFactory, $this->presenterFactory->createPresenter($this->name), $this->name);
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

	public function send(): PresenterResponse {
		return $this->request->send();
	}

	public function render(): string {
		return $this->request->send()->toString();
	}

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
