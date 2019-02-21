<?php declare(strict_types = 1);

use WebChemistry\Testing\TUnitTest;

class FileSystemTest extends \Codeception\Test\Unit {

	use TUnitTest;

	public function testRemoveRecursive() {
		@mkdir(__DIR__ . '/temp');
		@mkdir(__DIR__ . '/temp/temp2');
		@touch(__DIR__ . '/temp/file.txt');

		$this->services->fileSystem->removeDirRecursive(__DIR__ . '/temp');

		$this->assertTrue(!file_exists(__DIR__ . '/temp'));
	}

	public function testFileCount() {
		@mkdir(__DIR__ . '/temp');
		@mkdir(__DIR__ . '/temp/temp2');
		@touch(__DIR__ . '/temp/file.txt');
		@touch(__DIR__ . '/temp/file2.txt');

		$this->assertSame(2, $this->services->fileSystem->fileCount(__DIR__ . '/temp'));

		$this->services->fileSystem->removeDirRecursive(__DIR__ . '/temp');
	}

	public function testDirCount() {
		@mkdir(__DIR__ . '/temp');
		@mkdir(__DIR__ . '/temp/temp2');
		@touch(__DIR__ . '/temp/file.txt');
		@touch(__DIR__ . '/temp/file2.txt');

		$this->assertSame(1, $this->services->fileSystem->dirCount(__DIR__ . '/temp'));

		$this->services->fileSystem->removeDirRecursive(__DIR__ . '/temp');
	}

	public function testItemCount() {
		@mkdir(__DIR__ . '/temp');
		@mkdir(__DIR__ . '/temp/temp2');
		@touch(__DIR__ . '/temp/file.txt');
		@touch(__DIR__ . '/temp/file2.txt');

		$this->assertSame(3, $this->services->fileSystem->itemCount(__DIR__ . '/temp'));

		$this->services->fileSystem->removeDirRecursive(__DIR__ . '/temp');
	}

}