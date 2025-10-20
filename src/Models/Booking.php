<?php
namespace App\Models;
class Booking {
	public string $id;
	public string $slotId;
	public string $userEmail;
	public string $createdAt;
	public function __construct(array $data){
		$this->id = $data['id'];
		$this->slotId = $data['slotId'];
		$this->userEmail = $data['userEmail'];
		$this->createdAt = $data['createdAt'];
	}
}
