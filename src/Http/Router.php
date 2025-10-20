<?php
namespace App\Http;
use App\Controllers\SiteController;
/**
 * Routeur minimal : oriente les requêtes vers le contrôleur.
 */
class Router {
	private static ?SiteController $controller = null;
	public static function setController(SiteController $c): void { self::$controller = $c; }
	public function dispatch(Request $req): void {
		$path = $req->path();
		$method = $req->method();
		$ctrl = self::$controller;
		if(!$ctrl){ echo 'Controller not set'; return; }
		if($method === 'GET' && ($path === '/' || $path === '/index.php')){
			$ctrl->home($req->get);
			return;
		}
		if($method === 'POST' && ($path === '/' || $path === '/index.php')){
			$action = $req->post['action'] ?? '';
			try {
				if($action === 'addService') $ctrl->handleAddService($req->post);
				elseif($action === 'addSlot') $ctrl->handleAddSlot($req->post);
				elseif($action === 'deleteSlot') $ctrl->handleDeleteSlot($req->post);
				elseif($action === 'book') $ctrl->handleBook($req->post);
				elseif($action === 'cancel') $ctrl->handleCancel($req->post);
				else echo 'Unknown action';
			} catch(\Throwable $e){
				echo 'Error: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
			}
			return;
		}
		echo 'Not Found';
	}
}
