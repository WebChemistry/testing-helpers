<?php

declare(strict_types=1);

namespace Tests\Unit;

use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\TUnitTest;

class ControlTest extends \Codeception\Test\Unit {
	use TUnitTest;

	public function testAnalyzeParams(): void {
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

	public function testSendParams(): void {
		$request = $this->services->control->createRequest(new FooControl());

		$request->setControlParams([
			'foo' => 'bar',
		]);

		$this->assertSame('bar', $request->send()->getControl()->foo);
	}

	public function testSendParamsTwice(): void {
		$request = $this->services->control->createRequest(new FooControl());
		$request->setControlParams([
			'foo' => 'bar',
		]);

		$this->assertSame('bar', $request->send()->getControl()->foo);

		$request->setControlParams([
			'foo' => 'bar2',
		]);

		$this->assertSame('bar2', $request->send()->getControl()->foo);
	}

	public function testRenderString(): void {
		$request = $this->services->control->createRequest(new FooControl());
		$request->setControlParams([
			'foo' => 'bar',
		]);

		$source = $request->setRender()->send()->toString();
		$this->assertSame('test bar', trim((string) $source));
	}
}

class FooControl extends \Nette\Application\UI\Control {
	/** @persistent @var string */
	public $foo = null;

	public function render(): void {
		$this->template->setFile(__DIR__ . '/templates/basic.latte');

		$this->template->param = $this->foo;

		$this->template->render();
	}
}
