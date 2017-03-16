<?php

class HelperTest extends \Codeception\Test\Unit {

	use \WebChemistry\Testing\TUnitTest;

	protected function _before() {
	}

	protected function _after() {
	}

	public function testVariableAccess() {
		$this->assertInstanceOf(\WebChemistry\Testing\Services::class, $this->services);
	}

	public function testClassFormService() {
		$this->assertInstanceOf(\WebChemistry\Testing\Components\Form::class, $this->services->form);
		$this->assertInstanceOf(\WebChemistry\Testing\Components\Form::class, $this->services->getForm());
		$this->assertSame($this->services->getForm(), $this->services->getForm());
		$this->assertNotSame($this->services->getForm(), $this->services->getForm(TRUE));
		$this->assertSame($this->services->getForm(), $this->services->getForm());
	}

	public function testClassPresenterService() {
		$this->assertInstanceOf(\WebChemistry\Testing\Components\Presenter::class, $this->services->presenter);
		$this->assertInstanceOf(\WebChemistry\Testing\Components\Presenter::class, $this->services->getPresenter());
		$this->assertSame($this->services->presenter, $this->services->getPresenter());
	}

	public function testClassFileSystemService() {
		$this->assertInstanceOf(\WebChemistry\Testing\Components\FileSystem::class, $this->services->fileSystem);
		$this->assertInstanceOf(\WebChemistry\Testing\Components\FileSystem::class, $this->services->getFileSystem());
		$this->assertSame($this->services->getFileSystem(), $this->services->fileSystem);
	}

}