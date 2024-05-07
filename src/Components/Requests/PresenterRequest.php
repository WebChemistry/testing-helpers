<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components\Requests;

use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\PresenterFactory;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class PresenterRequest extends BaseRequest {

	/** @var IPresenter */
	private $presenter;

	/** @var string */
	private $presenterAction;

	public function __construct(PresenterFactory $presenterFactory, IPresenter $presenter, $name) {
		parent::__construct($presenterFactory, $name);

		$this->presenter = $presenter;
	}

	public function setControlParams(array $params) {
		throw new TestException('Cannot setControlParams in presenter.');
	}

	public function setPresenterAction(string $presenterAction): self {
		$this->presenterAction = $presenterAction;

		return $this;
	}

	public function send(): PresenterResponse {
		if ($this->presenterAction) {
			$this->params['action'] = $this->presenterAction;
		}
		$request = $this->createApplicationRequest();

		return new PresenterResponse($this->presenter->run($request), $this->presenter);
	}

}
