<?php

namespace WebChemistry\Testing\Components\Builders;

use Nette\Application\IPresenter;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use WebChemistry\Testing\Components\Helpers\FakePresenter;
use WebChemistry\Testing\Components\Presenter;
use WebChemistry\Testing\Components\Responses\FormResponse;

class FormSender {

	/** @var Form */
	private $form;

	/** @var string */
	private $name;

	/** @var array */
	private $files = [];

	/** @var array */
	private $post = [];

	/** @var Presenter */
	private $presenters;

	public function __construct(Form $form, Presenter $presenters, $name) {
		$this->form = $form;
		$this->name = $name;
		$this->presenters = $presenters;
	}

	/**
	 * @param array $files
	 * @return static
	 */
	public function setFiles(array $files) {
		$this->files = $files;

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $path
	 * @return static
	 */
	public function addFile($name, $path) {
		$this->files[$name] = new FileUpload([
			'name' => basename($path),
			'type' => NULL,
			'size' => filesize($path),
			'tmp_name' => $path,
		]);

		return $this;
	}

	/**
	 * @param string $name
	 * @param FileUpload $fileUpload
	 * @return static
	 */
	public function addFileUpload($name, FileUpload $fileUpload) {
		$this->files[$name] = $fileUpload;

		return $this;
	}

	/**
	 * @param array $post
	 * @return static
	 */
	public function setPost(array $post) {
		$this->post = $post;

		return $this;
	}

	/**
	 * @param string $name
	 * @param string $value
	 * @return static
	 */
	public function addPost($name, $value) {
		$this->post[$name] = $value;

		return $this;
	}

	/**
	 * @return FormResponse
	 */
	public function send() {
		/** @var FakePresenter $presenter */
		$presenter = $this->presenters->createPresenter('Fake');
		$presenter->startupComponents[$this->name] = $this->form;

		$response = $this->presenters->createRequest($presenter, 'POST', [
			'do' => $this->name . '-submit'
		], $this->post, $this->files);

		return new FormResponse($response, $this->name);
	}

}
