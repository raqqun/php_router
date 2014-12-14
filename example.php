<?php

/*
 *
 * This is an example controller
 * Controller can also be a class with methods as templates controllers
 */

include_once 'router.php';

$router = new Router(array('GET', 'POST'));


$router->register('GET', '/', 'home');
function home() {

    echo 'Hello, World.';

}

function default_404() {

    echo 'This is a 404 page';

}

$router->register('GET', 'users/(?P<id>\d+)/', 'get_user_id');
function get_user_id($id) {

    echo "Get user {$id}";

}


$router->register('GET', 'users/(?P<id>\d+)/(?P<name>\w+)/', 'get_user');
function get_user($id, $name) {

    echo "Get user {$id}, {$name}";

}

$router->dispatch();
