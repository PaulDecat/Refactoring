<?php
namespace App\Http;
class Response {
	private int $status = 200;
	private array $headers = [];
	public function setStatus(int $code): void { $this->status = $code; }
	public function setHeader(string $name, string $value): void { $this->headers[$name] = $value; }
	public function send(string $body): void {
		http_response_code($this->status);
		foreach($this->headers as $k=>$v) header($k . ': ' . $v);
		echo $body;
	}
}
