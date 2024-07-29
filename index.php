<?php
ob_start();

require __DIR__ . "/vendor/autoload.php";

/**
 * BOOTSTRAP
 */

use Source\Core\Session;
use CoffeeCode\Router\Router;


$session = new Session();
$route = new Router(url(), ":");

/*
 * WEB ROUTES
 */
$route->namespace("Source\App");
$route->get("/", "Web:home");
$route->get("/systemjw", "Web:system");
$route->get("/systemjw/{id}/{idmap}/{user}/{password}", "Web:system");
$route->get("/sobre", "Web:about");
$route->get("/termos", "Web:terms");
$route->get("/systemjwcheckupdate", "Web:systemjwcheckupdate");
$route->post("/systemjwcheckupdate/{idsmal}", "Web:systemjwcheckupdate");
//web rout ajax
$route->post("/mapbusca", "Web:mapbusca");
$route->post("/mapbusca/{user_id}", "Web:mapbusca");

$route->post("/resetmap", "Web:resetmaps");
$route->post("/resetmap/{user_id}", "Web:resetmaps");

//auth
$route->get("/entrar", "Web:login");
$route->post("/entrar", "Web:login");

$route->get("/cadastrar", "Web:register");
$route->post("/cadastrar", "Web:register");

$route->get("/recuperar", "Web:forget");

//optin
$route->get("/confirma", "Web:confirm");
$route->get("/obrigado/{email}", "Web:success");


/*
 * APP
 */
$route->group("app");
$route->get("/", "App:home");
$route->get("/sair", "App:logout");

//services



/*
 * ERROR ROUTES
 */
$route->namespace("Source\App")->group("/ops");
$route->get("/{errcode}", "Web:error");

/*
 * ROUTE
 */
$route->dispatch();

/*
 * ERROR REDIRECT
 */
if($route->error()) {
    $route->redirect("/ops/{$route->error()}");
}

ob_end_flush();