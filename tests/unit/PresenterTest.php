<?php declare(strict_types = 1);

use Nette\Application\Responses\TextResponse;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TUnitTest;

class PresenterTest extends \Codeception\Test\Unit {

	use TUnitTest;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testCreatePresenter() {
		$this->assertInstanceOf(MyPresenter::class, $this->services->presenter->createPresenter(MyPresenter::class));
		$this->assertNotSame(
			$this->services->presenter->createPresenter(MyPresenter::class),
			$this->services->presenter->createPresenter(MyPresenter::class)
		);
	}

}

class MyPresenter extends Presenter {

	public function actionDefault() {
		$this->sendResponse(new TextResponse('test'));
	}



}
