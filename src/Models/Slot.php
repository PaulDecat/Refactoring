<?php
namespace App\Models;
class Slot {
	public string $id;
	public string $serviceId;
	public string $datetime;
	public int $capacity;
	public function __construct(array $data){
		$this->id = $data['id'];
		$this->serviceId = $data['serviceId'];
		$this->datetime = $data['datetime'];
		$this->capacity = $data['capacity'] ?? 1;
	}
}
