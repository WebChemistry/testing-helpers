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

		$this->services->form->addForm('controlParams', function ($param) {
			$form = new Form();

			$form->addText($param);

			return $form;
		});
	}

	public function testSend() {
		$sender = $this->services->form->createSender('control');
		$sender->addPost('name', 'foo');
		$response = $sender->send();

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

		$sender = $this->services->form->createSender('control');
		$sender->addPost('name', 'foo');
		$sender->addPost('container', ['name' => 'bar']);
		$response = $sender->send();

		$this->assertSame('foo', $response->getValue('name'));
		$this->assertSame('bar', $response->getValue('container.name'));
	}

	public function testSendWithParameters() {
		$sender = $this->services->form->createSender('controlParams', 'input');

		$sender->addPost('input', 'val');

		$this->assertSame('val', $sender->send()->getValue('input'));
	}

	public function testCreateForm() {
		$form = $this->services->form->createForm('control');
		$this->assertSame('control1', $form->getName());

		$form = $this->services->form->createForm('control');
		$this->assertSame('control2', $form->getName());
	}

	public function testCreatePureForm() {
		$form = $this->services->form->createPureForm('control');
		$this->assertNull($form->getParent());
	}

	public function testCreateFormWithParameters() {
		$form = $this->services->form->createForm('controlParams', 'input');

		$this->assertNotNull($form->getComponent('input'));
	}

}
