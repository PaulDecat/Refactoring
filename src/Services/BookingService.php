<?php
namespace App\Services;
use App\Repository\DataStore;
/**
 * Service applicatif gérant les services, créneaux et réservations.
 * Encapsule la logique métier et persiste via DataStore.
 */
class BookingService {
	private DataStore $store;
	public function __construct(DataStore $store){ $this->store = $store; }
	/**
	 * Crée un service et retourne son identifiant.
	 */
	public function addService(string $name, ?string $desc=null, ?int $duration=null): string {
		$services = $this->store->getServices();
		$id = 'svc_' . (count($services)+1);
		$services[] = ['id'=>$id,'name'=>$name,'description'=>$desc,'duration'=>$duration];
		$this->store->saveServices($services);
		return $id;
	}
	/**
	 * Ajoute un créneau pour un service à une date donnée.
	 * Accepte les formats "YYYY-MM-DDTHH:MM" et "YYYY-MM-DD HH:MM".
	 */
	public function addSlot(string $serviceId, string $datetime, int $capacity=1): string {
		$dt = \DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
		if(!$dt){
			$dt = \DateTime::createFromFormat('Y-m-d H:i', $datetime);
		}
		if(!$dt) throw new \InvalidArgumentException('datetime invalid');
		$slots = $this->store->getSlots();
		$id = 'slt_' . (count($slots)+1);
		$slots[] = ['id'=>$id,'serviceId'=>$serviceId,'datetime'=>$dt->format('c'),'capacity'=>$capacity];
		$this->store->saveSlots($slots);
		return $id;
	}
	/**
	 * Supprime un créneau s'il n'a aucune réservation associée.
	 */
	public function deleteSlot(string $slotId): void {
		$bookings = $this->store->getBookings();
		foreach($bookings as $b){
			if($b['slotId'] === $slotId){
				throw new \RuntimeException('slot has bookings');
			}
		}
		$slots = $this->store->getSlots();
		$found = false;
		foreach($slots as $k=>$s){
			if($s['id'] === $slotId){ unset($slots[$k]); $found=true; }
		}
		if(!$found) throw new \RuntimeException('slot not found');
		$this->store->saveSlots(array_values($slots));
	}
	/**
	 * Réserve un créneau pour un utilisateur (contrôles: doublon, capacité, date passée).
	 */
	public function book(string $slotId, string $userEmail): string {
		$bookings = $this->store->getBookings();
		$slots = $this->store->getSlots();
		$slot = null; foreach($slots as $s) if($s['id']===$slotId) $slot=$s;
		if(!$slot) throw new \RuntimeException('slot not found');
		$slotTime = new \DateTime($slot['datetime']);
		if($slotTime < new \DateTime()) throw new \RuntimeException('slot in the past');
		foreach($bookings as $b){ if($b['slotId']===$slotId && $b['userEmail']===$userEmail) throw new \RuntimeException('double booking'); }
		$count = 0; foreach($bookings as $b) if($b['slotId']===$slotId) $count++;
		if($count >= ($slot['capacity'] ?? 1)) throw new \RuntimeException('slot full');
		$id = 'res_' . (count($bookings)+1);
		$bookings[] = ['id'=>$id,'slotId'=>$slotId,'userEmail'=>$userEmail,'createdAt'=>(new \DateTime())->format('c')];
		$this->store->saveBookings($bookings);
		return $id;
	}
	/**
	 * Annule une réservation si l'utilisateur est propriétaire et que le créneau est futur.
	 */
	public function cancel(string $bookingId, string $userEmail): bool {
		$bookings = $this->store->getBookings();
		foreach($bookings as $k=> $b){
			if($b['id']===$bookingId){
				if($b['userEmail'] !== $userEmail) throw new \RuntimeException('not owner');
				$slot = null; foreach($this->store->getSlots() as $s) if($s['id']===$b['slotId']) $slot=$s;
				if(!$slot) throw new \RuntimeException('slot not found');
				if(new \DateTime($slot['datetime']) <= new \DateTime()) throw new \RuntimeException('cannot cancel past or ongoing');
				unset($bookings[$k]);
				$this->store->saveBookings(array_values($bookings));
				return true;
			}
		}
		throw new \RuntimeException('booking not found');
	}
}
