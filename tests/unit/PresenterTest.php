<?php

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
		$this->assertInstanceOf('MyPresenter', $this->services->presenter->createPresenter('My'));
		$this->assertNotSame($this->services->presenter->createPresenter('My'), $this->services->presenter->createPresenter('My'));
	}

	// Todo: tests

}

class MyPresenter extends Presenter {

	public function actionDefault() {
		$this->sendResponse(new TextResponse('test'));
	}



}
