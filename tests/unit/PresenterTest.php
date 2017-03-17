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
		$this->assertInstanceOf(MyPresenter::class, $this->services->presenter->createPresenter('My'));
		$this->assertNotSame($this->services->presenter->createPresenter('My'), $this->services->presenter->createPresenter('My'));
	}

	public function testCreateRequest() {
		$response = $this->services->presenter->createRequest('My');

		$this->assertInstanceOf(PresenterResponse::class, $response);
		$this->assertInstanceOf(TextResponse::class, $response->getResponse());
		$this->assertSame('test', $response->getResponse()->getSource());
	}

}

class MyPresenter extends Presenter {

	public function actionDefault() {
		$this->sendResponse(new TextResponse('test'));
	}



}
