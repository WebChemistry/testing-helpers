<?php declare(strict_types = 1);

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\TUnitTest;

class HierarchyTest extends \Codeception\Test\Unit {

	use TUnitTest;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testSendPresenter() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$this->assertSame('Test', $hierarchy->send()->toString());
	}

	public function testSendPresenterAction() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->setAction('foo');

		$this->assertSame('Test Foo action', $hierarchy->send()->toString());
	}

	public function testSendPresenterHandle() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->addParams(['param' => 'handle']);

		$this->assertSame('Test handle', $hierarchy->sendSignal('test')->toString());
	}

	public function testPresenterGetControl() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$this->assertInstanceOf('MyControl', $hierarchy->getControl('control')->getObject());
	}

	public function testControlSignal() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$response = $hierarchy->getControl('control')->addParams(['param2' => 'test'])->sendSignal('test');
		$this->assertSame('Test handle-control-test', $response->toString());
		$this->assertInstanceOf('Nette\Application\UI\Control', $response->getControl());
		$this->assertInstanceOf('Nette\Application\UI\Presenter', $response->getPresenter());
		$this->assertSame('handle-control-test', $response->getControl()->param);
	}

	public function testControlPersistentParam() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->getControl('control')->addParams(['param' => 'test']);

		$this->assertSame('Test test', $hierarchy->send()->toString());
	}

	public function testControlRender() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->getControl('control')->addParams(['param' => 'foo']);

		$this->assertSame('foo', $hierarchy->getControl('control')->render());
	}

	public function testSubControlRender() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$this->assertSame('foo',
			$hierarchy->getControl('control')->getControl('control')->addParams(['param' => 'foo'])->render());
	}

	public function testRenderPresenterForm() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->setAction('form')->render();

		$dom = $hierarchy->renderDomQuery();
		$this->assertTrue($dom->has('form#frm-form'));
	}

	public function testSendForm() {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class)->setAction('form');

		$response = $hierarchy->getForm('form')->setValues([
			'name' => 'Foo',
		])->send();

		$this->assertTrue($response->isSubmitted());
		$this->assertTrue($response->isSuccess());
		$this->assertSame('Foo', $response->getValue('name'));

		$dom = $response->toDomQuery();
		$this->assertTrue($dom->has('input[value="Foo"]'));
	}
}

class HiPresenter extends Presenter {

	public function actionDefault() {
		$this->template->setFile(__DIR__ . '/templates/hierarchy.latte');
	}

	public function actionFoo() {
		$this->template->setFile(__DIR__ . '/templates/hierarchy.latte');
		$this->template->param = 'Foo action';
	}

	public function actionForm() {
		$this->template->setFile(__DIR__ . '/templates/hierarchy-form.latte');
	}

	public function handleTest($param) {
		$this->template->param = $param;
	}

	protected function createComponentControl() {
		return new MyControl();
	}

	protected function createComponentForm() {
		$form = new Form();

		$form->addText('name', 'Name');

		$form->onSuccess[] = function () {};

		return $form;
	}

}

class MyControl extends Control {

	/** @persistent */
	public $param;

	public function render() {
		$this->template->setFile(__DIR__ . '/templates/control.latte');

		$this->template->param = $this->param;

		$this->template->render();
	}

	protected function createComponentControl() {
		return new self();
	}

	public function handleTest($param2) {
		$this->param = 'handle-control-' . $param2;
		$this->template->param = $this->param;
	}

}
