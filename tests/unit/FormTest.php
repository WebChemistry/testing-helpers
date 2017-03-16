<?php

use Nette\Application\UI\Form;
use WebChemistry\Testing\Components\Responses\FormResponse;
use WebChemistry\Testing\TUnitTest;

class FormTest extends \Codeception\Test\Unit {

	use TUnitTest;

	public function _before() {
		$this->services->form->addForm('control', function () {
			$form = new Form();

			$form->addText('name');

			return $form;
		});
	}

	public function testSend() {
		$response = $this->services->form->send('control', ['name' => 'foo']);

		$this->assertInstanceOf(FormResponse::class, $response);
		$this->assertTrue($response->getForm()->isSubmitted());
		$this->assertSame('foo', $response->getForm()->getValues()['name']);
	}

	public function testGetValue() {
		$this->services->form->addForm('control', function () {
			$form = new Form();

			$form->addText('name');
			$form->addContainer('container')
				->addText('name');

			return $form;
		});

		$response = $this->services->form->send('control', ['name' => 'foo', 'container' => ['name' => 'bar']]);

		$this->assertSame('foo', $response->getValue('name'));
		$this->assertSame('bar', $response->getValue('container.name'));
	}

	public function testActionCallback() {
		$response = $this->services->form->send('control', [], [], function (Form $form) {
			$form['name']->setValue('foo');
		});

		$this->assertSame('foo', $response->getValue('name'));
	}

}
