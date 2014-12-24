<?php

/*
 *
 * This is an example controller
 * Controller can also be a class with methods as templates controllers
 */

include_once 'router.php';

$router = new Router();

$router->register('GET', '/', 'home');
function home() {

    echo 'Hello, World.';

}

$router->register(array('GET', 'POST'), 'users/(?P<id>\d+)/', 'get_user_id');
function get_user_id($id) {

    echo "Get user {$id}";

}


$router->register('GET', 'users/(?P<id>\d+)/(?P<name>\w+)/', 'get_user');
function get_user($id, $name) {

    echo "Get user {$id}, {$name}";

}

$router->dispatch();
