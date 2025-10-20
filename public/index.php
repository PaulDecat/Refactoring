<?php
require_once __DIR__ . '/../src/bootstrap.php';
use App\Http\Request;
use App\Http\Router;
$router = new Router();
$router->dispatch(Request::fromGlobals());
