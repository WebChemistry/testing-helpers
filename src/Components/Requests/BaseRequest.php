<?php

namespace WebChemistry\Testing\Components\Requests;

use Nette\Application\IPresenter;
use Nette\Application\Request;
use Nette\Http\FileUpload;
use WebChemistry\Testing\Components\Helpers\Helpers;
use WebChemistry\Testing\Components\Presenter;
use WebChemistry\Testing\Components\Responses\PresenterResponse;

abstract class BaseRequest {

	/** @var array */
	protected $post = [];

	/** @var array */
	protected $files = [];

	/** @var array */
	protected $params = [];

	/** @var Presenter */
	protected $presenterService;

	/** @var string */
	protected $name;

	/** @var string */
	protected $signal;

	/** @var string */
	protected $method;

	public function __construct(Presenter $presenterService, $name) {
		$this->presenterService = $presenterService;
		$this->name = $name;
	}

	abstract public function send();

	// Posts

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
	 * @param array $post
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
	 * @param array $files
	 * @return static
	 */
	public function setFiles(array $files) {
		$this->files = $files;

		return $this;
	}

	// Params

	/**
	 * @param array $params
	 * @return static
	 */
	public function setControlParams(array $params) {
		Helpers::analyzeParams($params, $this->name);
		$this->params = $params;

		return $this;
	}

	/**
	 * @param array $params
	 * @return static
	 */
	public function setParams(array $params) {
		$this->params = $params;

		return $this;
	}

	/**
	 * @param string $name
	 * @param mixed $value
	 * @return static
	 */
	public function addParam($name, $value) {
		$this->params[$name] = $value;

		return $this;
	}

	/**
	 * @param array $params
	 * @return static
	 */
	public function addParams(array $params) {
		$this->params = array_merge_recursive($this->params, $params);

		return $this;
	}

	/**
	 * @param string $action
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
	 * @param IPresenter $presenter
	 * @return PresenterResponse
	 */
	protected function createRequest(IPresenter $presenter) {
		$request = $this->presenterService->createRequest($presenter);

		$request->setMethod($this->method);
		$request->setParams($this->params);
		$request->setPost($this->post);
		$request->setSignal($this->signal);
		$request->setFiles($this->files);

		return $request->send();
	}

}
