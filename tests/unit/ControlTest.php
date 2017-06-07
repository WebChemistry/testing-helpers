<?php

use WebChemistry\Testing\Components\Control;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\TUnitTest;

class ControlTest extends \Codeception\Test\Unit {

	use TUnitTest;

	protected function _before() {
		$this->services->control->addControl('custom', function () {
			return new FooControl();
		});
	}

	public function testAnalyzeParams() {
		$array = [
			'foo' => 'value',
			'bar' => [
				'param',
			],
			'control-param' => 'val',
		];
		Helpers::analyzeParams($array, 'control');

		$this->assertEquals([
			'control-foo' => 'value',
			'control-bar' => [
				'param',
			],
			'control-param' => 'val',
		], $array);
	}

	public function testSendParams() {
		$request = $this->services->control->createRequest('custom');

		$request->setControlParams([
			'foo' => 'bar',
		]);

		$this->assertSame('bar', $request->send()->getControl()->foo);
	}

	public function testSendParamsTwice() {
		$request = $this->services->control->createRequest('custom');
		$request->setControlParams([
			'foo' => 'bar',
		]);

		$this->assertSame('bar', $request->send()->getControl()->foo);

		$request->setControlParams([
			'foo' => 'bar2',
		]);

		$this->assertSame('bar2', $request->send()->getControl()->foo);
	}

	public function testRenderString() {
		$request = $this->services->control->createRequest('custom');
		$request->setControlParams([
			'foo' => 'bar',
		]);

		$source = $request->setRender()->send()->toString();
		$this->assertSame('test bar', trim((string) $source));
	}

}

class FooControl extends \Nette\Application\UI\Control {

	/** @persistent @var string */
	public $foo = NULL;

	public function render() {
		$this->template->setFile(__DIR__ . '/templates/basic.latte');

		$this->template->param = $this->foo;

		$this->template->render();
	}

}
