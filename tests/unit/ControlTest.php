<?php

use WebChemistry\Testing\Components\Control;
use WebChemistry\Testing\TUnitTest;

class ControlTest extends \Codeception\Test\Unit {

	use TUnitTest;

	protected function _before() {
		$this->services->control->addControl('custom', function () {
			return new FooControl();
		});
	}

	protected function _after() {
	}

	public function testAnalyzeParams() {
		$callback = $this->reflection->getMethodCallback(Control::class, 'analyzeParams');

		$array = [
			'foo' => 'value',
			'bar' => [
				'param',
			],
			'control-param' => 'val',
		];
		$callback($array, 'control');

		$this->assertEquals([
			'control-foo' => 'value',
			'control-bar' => [
				'param',
			],
			'control-param' => 'val',
		], $array);
	}

	public function testRenderToString() {
		$response = $this->services->control->renderToString('custom');
		$this->assertSame('test', $response);
	}

	public function testRenderToStringSendParam() {
		$response = $this->services->control->renderToString('custom', [
			'param' => 'str',
		]);

		$this->assertSame('test str', $response);
	}

	public function testRenderToStringActionCallback() {
		$response = $this->services->control->renderToString('custom', [], function (FooControl $control) {
			$control->param = 'bar';
		});

		$this->assertSame('test bar', $response);
	}

	public function testSendRequest() {
		$response = $this->services->control->sendRequest('custom', [], function (FooControl $control) {
			$control->param = 'bar';
		});

		return $response->getControl()->param;
	}

}

class FooControl extends \Nette\Application\UI\Control {

	/** @persistent @var string */
	public $param = NULL;

	public function render() {
		$this->template->setFile(__DIR__ . '/templates/basic.latte');

		$this->template->param = $this->param;

		$this->template->render();
	}

}
