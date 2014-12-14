<?php

/*
 *
 * This is an example controller
 *
 */

include_once 'router.php';

$router = new Router();


$router->register('GET', '/', 'home');
function home() {

    echo 'Hello, World.';

}

// $router->register('GET', null, 'default_404');
function default_404() {

    echo 'This is a 404 page';

}

$router->register('GET', '/users/(?P<id>\d+)/', 'get_user_id');
function get_user_id($id) {

    echo "Get user {$id}";

}


$router->register('GET', '/users/(?P<id>\d+)/(?P<name>\w+)/', 'get_user');
function get_user($id, $name) {

    echo "Get user {$id}, {$name}";

}

$router->dispatch();
