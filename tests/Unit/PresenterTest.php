<?php

declare(strict_types=1);

namespace Tests\Unit;

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\TUnitTest;

class PresenterTest extends \Codeception\Test\Unit {
	use TUnitTest;

	protected function _before(): void {
	}

	protected function _after(): void {
	}

	public function testCreatePresenter(): void {
		$this->assertInstanceOf(MyPresenter::class, $this->services->presenterFactory->createPresenter(MyPresenter::class));
		$this->assertNotSame(
			$this->services->presenterFactory->createPresenter(MyPresenter::class),
			$this->services->presenterFactory->createPresenter(MyPresenter::class)
		);
	}
}

class MyPresenter extends Presenter {
	public function actionDefault(): void {
		$this->sendResponse(new TextResponse('test'));
	}
}
