<?php declare(strict_types = 1);

namespace WebChemistry\Testing\Components;

class FileSystem {

	/**
	 * Removes directory $dir (including) and sub-directories if contains
	 *
	 * @param string $dir
	 */
	public function removeDirRecursive(string $dir): void {
		$this->rmDir($dir);
	}

	/**
	 * Returns count of directories, files, ... without . and ..
	 *
	 * @param string $dir
	 * @return int
	 */
	public function itemCount(string $dir): int {
		$objects = scandir($dir);
		$count = 0;
		foreach ($objects as $object) {
			if ($object === '.' || $object === '..') {
				continue;
			}
			$count++;
		}

		return $count;
	}

	/**
	 * Returns count of files
	 *
	 * @param string $dir
	 * @return int
	 */
	public function fileCount($dir) {
		$objects = scandir($dir);
		$count = 0;
		foreach ($objects as $object) {
			if ($object === '.' || $object === '..') {
				continue;
			}
			if (is_file($dir . '/' . $object)) {
				$count++;
			}
		}

		return $count;
	}

	/**
	 * Returns count of directories
	 *
	 * @param string $dir
	 * @return int
	 */
	public function dirCount(string $dir): int {
		$objects = scandir($dir);
		$count = 0;
		foreach ($objects as $object) {
			if ($object === '.' || $object === '..') {
				continue;
			}
			if (is_dir($dir . '/' . $object)) {
				$count++;
			}
		}

		return $count;
	}

	protected function rmDir(string $dir): void {
		if (is_dir($dir)) {
			$objects = scandir($dir);
			foreach ($objects as $object) {
				if ($object !== '.' && $object !== '..') {
					if (is_dir($dir . '/' . $object)) {
						$this->rmDir($dir . '/' . $object);
					} else {
						unlink($dir . '/' . $object);
					}
				}
			}
			rmdir($dir);
		}
	}

}
