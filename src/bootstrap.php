<?php
/**
 * Bootstrap minimal de l'application (chargements et initialisation DI simple).
 */
require_once __DIR__ . '/Http/Request.php';
require_once __DIR__ . '/Http/Response.php';
require_once __DIR__ . '/Http/Router.php';
require_once __DIR__ . '/Repository/DataStore.php';
require_once __DIR__ . '/Models/Service.php';
require_once __DIR__ . '/Models/Slot.php';
require_once __DIR__ . '/Models/Booking.php';
require_once __DIR__ . '/Services/BookingService.php';
require_once __DIR__ . '/Controllers/SiteController.php';
$appDataPath = __DIR__ . '/../data';
$dataStore = new App\Repository\DataStore($appDataPath);
$bookingService = new App\Services\BookingService($dataStore);
$siteController = new App\Controllers\SiteController($bookingService, $dataStore);
App\Http\Router::setController($siteController);
