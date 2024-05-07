<?php

declare(strict_types=1);

namespace Tests\Support\Helper;

use Nette\Application\UI\Form;

final class FormTester {
	public static function create(): Form {
		$form = new Form();

		$form->addText('name');

		$form->onSuccess[] = function (): void {};

		return $form;
	}
}
