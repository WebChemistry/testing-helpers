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
	protected array $post = [];

	protected array $files = [];

	protected array $params = [];

	protected PresenterFactory $presenterFactory;

	protected string $name;

	protected ?string $signal = null;

	protected ?string $method = null;

	public function __construct(PresenterFactory $presenterFactory, $name) {
		$this->presenterFactory = $presenterFactory;
		$this->name = $name;
	}

	abstract public function send();

	// Posts

	public function setPost(array $post): static {
		$this->post = $post;

		return $this;
	}

	public function addPost(string $name, $value): static {
		$this->post[$name] = $value;

		return $this;
	}

	public function addPosts(array $post): static {
		$this->post = array_merge_recursive($this->post, $post);

		return $this;
	}

	// Files

	public function addFile(string $name, string $path): static {
		$this->files[$name] = new FileUpload([
			'name' => basename($path),
			'type' => null,
			'size' => filesize($path),
			'tmp_name' => $path,
		]);

		return $this;
	}

	public function addFileUpload(string $name, FileUpload $fileUpload): static {
		$this->files[$name] = $fileUpload;

		return $this;
	}

	public function setFiles(array $files): static {
		$this->files = $files;

		return $this;
	}

	// Params

	public function setControlParams(array $params): static {
		Helpers::analyzeParams($params, $this->name);
		$this->params = $params;

		return $this;
	}

	public function setParams(array $params): static {
		$this->params = $params;

		return $this;
	}

	public function addParam(string $name, $value): static {
		$this->params[$name] = $value;

		return $this;
	}

	public function addParams(array $params): static {
		$this->params = array_merge_recursive($this->params, $params);

		return $this;
	}

	public function setSignal(?string $action): static {
		$this->signal = $action;

		return $this;
	}

	// Method

	public function setMethod(?string $method): void {
		$this->method = $method;
	}

	protected function createApplicationRequest(): Request {
		if ($this->signal !== null) {
			$this->params['do'] = $this->signal;
		}

		return new Request($this->name, $this->method ?? 'GET', $this->params, $this->post, $this->files, []);
	}

	protected function createRequest(IPresenter $presenter): PresenterResponse {
		$request = $this->presenterFactory->createRequest($presenter);

		$request->setMethod($this->method);
		$request->setParams($this->params);
		$request->setPost($this->post);
		$request->setSignal($this->signal);
		$request->setFiles($this->files);

		return $request->send();
	}
}
