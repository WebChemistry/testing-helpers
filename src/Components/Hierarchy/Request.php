<?php

namespace WebChemistry\Testing\Components\Hierarchy;

use Nette\Application\IPresenter;
use WebChemistry\Testing\Components\Responses\PresenterResponse;
use WebChemistry\Testing\TestException;

class Request {

	/** @var \Nette\Application\Request */
	private $request;

	/** @var array */
	private $post = [];

	/** @var array */
	private $parameters = [];

	/** @var array */
	private $files = [];

	/** @var string */
	private $executor = NULL;

	/** @var IPresenter */
	private $presenter;

	/** @var string */
	private $signal;

	/** @var string */
	private $action;

	public function __construct($name, IPresenter $presenter) {
		$this->request = new \Nette\Application\Request($name);
		$this->presenter = $presenter;
	}

	/**
	 * @param string $action
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	public function setSignal($signal) {
		$this->signal = $signal;
	}

	protected function checkExecutor($executor) {
		if ($this->executor === NULL) {
			$this->executor = $executor;
		} else if ($this->executor !== $executor) {
			throw new TestException('You can send only one form.');
		}
	}

	public function setPost($executor = NULL) {
		$this->checkExecutor($executor);
		$this->request->setMethod('POST');
	}

	public function addPost(array $post) {
		$this->post = array_merge($this->post, $post);
	}

	public function addParameters(array $params) {
		$this->parameters = array_merge($this->parameters, $params);
	}

	public function addFile($name, $value) {
		$this->files[$name] = $value;
	}

	public function setFlag($flag, $value = TRUE) {
		$this->request->setFlag($flag, $value);
	}

	/**
	 * @return \Nette\Application\Request
	 */
	public function getRequest() {
		if ($this->signal) {
			$this->parameters['do'] = $this->signal;
		}
		if ($this->action) {
			$this->parameters['action'] = $this->action;
		}
		$this->request->setParameters($this->parameters);
		$this->request->setFiles($this->files);
		$this->request->setPost($this->post);

		return $this->request;
	}

	/**
	 * @return PresenterResponse
	 */
	public function sendRequest() {
		return new PresenterResponse($this->presenter->run($this->getRequest()), $this->presenter);
	}

}
