<?php

declare(strict_types=1);

namespace Tests\Unit;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use WebChemistry\Testing\TUnitTest;

class HierarchyTest extends \Codeception\Test\Unit {
	use TUnitTest;

	protected function _before(): void {
	}

	protected function _after(): void {
	}

	public function testSendPresenter(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$this->assertSame('Test', $hierarchy->send()->toString());
	}

	public function testSendPresenterAction(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->setAction('foo');

		$this->assertSame('Test Foo action', $hierarchy->send()->toString());
	}

	public function testSendPresenterHandle(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->addParams(['param' => 'handle']);

		$this->assertSame('Test handle', $hierarchy->sendSignal('test')->toString());
	}

	public function testPresenterGetControl(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$this->assertInstanceOf(MyControl::class, $hierarchy->getControl('control')->getObject());
	}

	public function testControlSignal(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$response = $hierarchy->getControl('control')->addParams(['param2' => 'test'])->sendSignal('test');
		$this->assertSame('Test handle-control-test', $response->toString());
		$this->assertInstanceOf(Control::class, $response->getControl());
		$this->assertInstanceOf(Presenter::class, $response->getPresenter());
		$this->assertSame('handle-control-test', $response->getControl()->param);
	}

	public function testControlPersistentParam(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->getControl('control')->addParams(['param' => 'test']);

		$this->assertSame('Test test', $hierarchy->send()->toString());
	}

	public function testControlRender(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->getControl('control')->addParams(['param' => 'foo']);

		$this->assertSame('foo', $hierarchy->getControl('control')->render());
	}

	public function testSubControlRender(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);

		$this->assertSame(
			'foo',
			$hierarchy->getControl('control')->getControl('control')->addParams(['param' => 'foo'])->render()
		);
	}

	public function testRenderPresenterFormControl(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class);
		$hierarchy->setAction('formControl')->render();

		$dom = $hierarchy->toDomQuery();
		$this->assertTrue($dom->has('form#frm-form'));
	}

	public function testSendForm(): void {
		$hierarchy = $this->services->hierarchy->createHierarchy(HiPresenter::class)->setAction('formControl');

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
	public function actionDefault(): void {
		$this->template->setFile(__DIR__ . '/templates/hierarchy.latte');
	}

	public function actionFoo(): void {
		$this->template->setFile(__DIR__ . '/templates/hierarchy.latte');
		$this->template->param = 'Foo action';
	}

	public function actionFormControl(): void {
		$this->template->setFile(__DIR__ . '/templates/hierarchy-form-control.latte');
	}

	public function handleTest($param): void {
		$this->template->param = $param;
	}

	protected function createComponentControl() {
		return new MyControl();
	}

	protected function createComponentForm() {
		$form = new Form();

		$form->addText('name', 'Name');

		$form->onSuccess[] = function (): void {};

		return $form;
	}
}

class MyControl extends Control {
	/** @persistent */
	public $param;

	public function render(): void {
		$this->template->setFile(__DIR__ . '/templates/control.latte');

		$this->template->param = $this->param;

		$this->template->render();
	}

	protected function createComponentControl() {
		return new self();
	}

	public function handleTest($param2): void {
		$this->param = 'handle-control-' . $param2;
		$this->template->param = $this->param;
	}
}
