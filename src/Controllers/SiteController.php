<?php
namespace App\Controllers;
use App\Services\BookingService;
use App\Repository\DataStore;
/**
 * Contrôleur principal: rend la page d'accueil et traite les actions POST.
 */
class SiteController {
	private BookingService $svc;
	private DataStore $store;
	public function __construct(BookingService $svc, DataStore $store){ $this->svc=$svc; $this->store=$store; }
	/**
	 * Page d'accueil: liste services, créneaux et réservations utilisateur.
	 */
	public function home(array $params){
		$email = $_COOKIE['email'] ?? ($params['mail'] ?? null);
		if(isset($params['mail'])) setcookie('email', $params['mail']);
		$services = $this->store->getServices();
		$slots = $this->store->getSlots();
		$bookings = $this->store->getBookings();
		include __DIR__ . '/../../templates/home.php';
	}
	/** Ajout de service */
	public function handleAddService(array $params): void {
		$name = trim($params['name'] ?? '');
		$desc = trim($params['description'] ?? '');
		$dur = $params['duration'] !== '' ? (int)$params['duration'] : null;
		if($name==='') throw new \InvalidArgumentException('name required');
		$this->svc->addService($name, $desc!==''?$desc:null, $dur);
		header('Location: /');
	}
	/** Ajout de créneau */
	public function handleAddSlot(array $params): void {
		$serviceId = $params['serviceId'] ?? '';
		$datetime = $params['datetime'] ?? '';
		$capacity = (int)($params['capacity'] ?? 1);
		if($serviceId==='' || $datetime==='') throw new \InvalidArgumentException('missing fields');
		$this->svc->addSlot($serviceId, $datetime, $capacity);
		header('Location: /');
	}
	/** Suppression de créneau */
	public function handleDeleteSlot(array $params): void {
		$slotId = $params['slotId'] ?? '';
		if($slotId==='') throw new \InvalidArgumentException('missing fields');
		$this->svc->deleteSlot($slotId);
		header('Location: /');
	}
	/** Réservation */
	public function handleBook(array $params): void {
		$slotId = $params['slotId'] ?? '';
		$userEmail = $_COOKIE['email'] ?? ($params['userEmail'] ?? '');
		if($slotId==='' || $userEmail==='') throw new \InvalidArgumentException('missing fields');
		$this->svc->book($slotId, $userEmail);
		header('Location: /');
	}
	/** Annulation de réservation */
	public function handleCancel(array $params): void {
		$bookingId = $params['bookingId'] ?? '';
		$userEmail = $_COOKIE['email'] ?? ($params['userEmail'] ?? '');
		if($bookingId==='' || $userEmail==='') throw new \InvalidArgumentException('missing fields');
		$this->svc->cancel($bookingId, $userEmail);
		header('Location: /');
	}
}
