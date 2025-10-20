<?php
namespace App\Models;
class Service {
	public string $id;
	public string $name;
	public ?string $description;
	public ?int $duration;
	public function __construct(array $data){
		$this->id = $data['id'];
		$this->name = $data['name'];
		$this->description = $data['description'] ?? null;
		$this->duration = isset($data['duration']) ? (int)$data['duration'] : null;
	}
}
