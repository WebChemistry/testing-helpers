<?php declare(strict_types = 1);

use Nette\Application\UI\Form;

final class FormTester {

	public static function create(): Form {
		$form = new Form();

		$form->addText('name');

		$form->onSuccess[] = function () {};

		return $form;
	}

}
