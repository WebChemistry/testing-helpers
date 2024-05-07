<?php

declare(strict_types=1);

namespace WebChemistry\Testing\Components\Requests;

use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Http\FileUpload;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\PresenterFactory;
use WebChemistry\Testing\Components\Responses\PresenterResponse;

abstract class BaseRequest {
	/** @var array */
	protected $post = [];

	/** @var array */
	protected $files = [];

	/** @var array */
	protected $params = [];

	/** @var PresenterFactory */
	protected $presenterFactory;

	/** @var string */
	protected $name;

	/** @var string */
	protected $signal;

	/** @var string */
	protected $method;

	public function __construct(PresenterFactory $presenterFactory, $name) {
		$this->presenterFactory = $presenterFactory;
		$this->name = $name;
	}

	abstract public function send();

	// Posts

	/**
	 * @return static
	 */
	public function setPost(array $post) {
		$this->post = $post;

		return $this;
	}

	/**
	 * @return static
	 */
	public function addPost(string $name, $value) {
		$this->post[$name] = $value;

		return $this;
	}

	/**
	 * @return static
	 */
	public function addPosts(array $post) {
		$this->post = array_merge_recursive($this->post, $post);

		return $this;
	}

	// Files

	/**
	 * @param string $name
	 * @param string $path
	 *
	 * @return static
	 */
	public function addFile($name, $path) {
		$this->files[$name] = new FileUpload([
			'name' => basename($path),
			'type' => null,
			'size' => filesize($path),
			'tmp_name' => $path,
		]);

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return static
	 */
	public function addFileUpload($name, FileUpload $fileUpload) {
		$this->files[$name] = $fileUpload;

		return $this;
	}

	/**
	 * @return static
	 */
	public function setFiles(array $files) {
		$this->files = $files;

		return $this;
	}

	// Params

	/**
	 * @return static
	 */
	public function setControlParams(array $params) {
		Helpers::analyzeParams($params, $this->name);
		$this->params = $params;

		return $this;
	}

	/**
	 * @return static
	 */
	public function setParams(array $params) {
		$this->params = $params;

		return $this;
	}

	/**
	 * @param string $name
	 *
	 * @return static
	 */
	public function addParam($name, $value) {
		$this->params[$name] = $value;

		return $this;
	}

	/**
	 * @return static
	 */
	public function addParams(array $params) {
		$this->params = array_merge_recursive($this->params, $params);

		return $this;
	}

	/**
	 * @param string $action
	 *
	 * @return static
	 */
	public function setSignal($action) {
		$this->signal = $action;

		return $this;
	}

	// Method

	/**
	 * @param string $method
	 */
	public function setMethod($method) {
		$this->method = $method;
	}

	/**
	 * @return Request
	 */
	protected function createApplicationRequest() {
		if ($this->signal) {
			$this->params['do'] = $this->signal;
		}

		return new Request($this->name, $this->method ?: 'GET', $this->params, $this->post, $this->files, []);
	}

	/**
	 * @return PresenterResponse
	 */
	protected function createRequest(IPresenter $presenter) {
		$request = $this->presenterFactory->createRequest($presenter);

		$request->setMethod($this->method);
		$request->setParams($this->params);
		$request->setPost($this->post);
		$request->setSignal($this->signal);
		$request->setFiles($this->files);

		return $request->send();
	}
}
