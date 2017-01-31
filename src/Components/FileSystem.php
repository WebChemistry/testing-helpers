<?php

namespace WebChemistry\Test\Components;

class FileSystem {

	public function removeDirRecursive($dir) {
		$this->rmDir($dir);
	}

	protected function rmDir($dir) {
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

	public function fileCount($dir) {
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

}
