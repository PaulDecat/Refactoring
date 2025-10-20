<?php
namespace App\Repository;
/**
 * Stockage JSON fichier pour services, créneaux, réservations et utilisateurs.
 */
class DataStore {
	private string $path;
	private array $data = [];
	public function __construct(string $path){
		$this->path = rtrim($path, '/');
		if(!is_dir($this->path)) mkdir($this->path, 0755, true);
		$this->load();
	}
	private function load(): void {
		$files = ['services.json','slots.json','bookings.json','users.json'];
		foreach($files as $f){
			$p = $this->path . '/' . $f;
			if(!file_exists($p)) file_put_contents($p, json_encode([]));
			$this->data[$f] = json_decode(file_get_contents($p), true) ?: [];
		}
	}
	private function saveFile(string $name): void {
		file_put_contents($this->path . '/' . $name, json_encode($this->data[$name], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
	}
	public function getServices(): array{ return $this->data['services.json']; }
	public function saveServices(array $arr): void { $this->data['services.json']=$arr; $this->saveFile('services.json'); }
	public function getSlots(): array{ return $this->data['slots.json']; }
	public function saveSlots(array $arr): void { $this->data['slots.json']=$arr; $this->saveFile('slots.json'); }
	public function getBookings(): array{ return $this->data['bookings.json']; }
	public function saveBookings(array $arr): void { $this->data['bookings.json']=$arr; $this->saveFile('bookings.json'); }
	public function getUsers(): array{ return $this->data['users.json']; }
	public function saveUsers(array $arr): void { $this->data['users.json']=$arr; $this->saveFile('users.json'); }
}
