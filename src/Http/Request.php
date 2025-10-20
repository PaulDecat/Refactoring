<?php
namespace App\Http;
class Request {
	public array $get;
	public array $post;
	public array $server;
	public array $cookies;
	public function __construct(array $get, array $post, array $server, array $cookies){
		$this->get = $get;
		$this->post = $post;
		$this->server = $server;
		$this->cookies = $cookies;
	}
	public static function fromGlobals(): self {
		return new self($_GET, $_POST, $_SERVER, $_COOKIE);
	}
	public function method(): string { return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET'); }
	public function path(): string {
		$uri = $this->server['REQUEST_URI'] ?? '/';
		$pos = strpos($uri, '?');
		return $pos === false ? $uri : substr($uri, 0, $pos);
	}
}
