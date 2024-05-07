<?php

declare(strict_types=1);

namespace Tests\Unit;

use Nette\Application\UI\Form;
use Tests\Support\Helper\FormTester;
use WebChemistry\Testing\Components\Responses\FormResponse;
use WebChemistry\Testing\TUnitTest;

class FormTest extends \Codeception\Test\Unit {
	use TUnitTest;

	public function testSend() {
		$sender = $this->services->form->createRequest(FormTester::create());
		$sender->addPost('name', 'foo');
		$response = $sender->send();

		$this->assertInstanceOf(FormResponse::class, $response);
		$this->assertTrue($response->getForm()->isSubmitted());
		$this->assertSame('foo', $response->getForm()->getValues()['name']);
	}

	public function testGetValue() {
		$sender = $this->services->form->createRequest(FormTester::create());
		$sender->addPost('name', 'foo');
		$response = $sender->send();

		$this->assertSame('foo', $response->getValue('name'));
	}

	public function testRequestRender() {
		$request = $this->services->form->createRequest(FormTester::create());
		$response = $request->render();

		$this->assertFalse($response->isSubmitted());
	}

	public function testActionCallback() {
		$request = $this->services->form->createRequest(FormTester::create())->setActionCallback(function (Form $form) {
			$form->addText('action');
		})->setRenderCallback(function (Form $form) {
			$form->addText('render');
		});
		$response = $request->render();

		$form = $response->getForm();

		$this->assertTrue(isset($form['action']));
		$this->assertTrue(isset($form['render']));
	}
}
