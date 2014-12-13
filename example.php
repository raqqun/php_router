<?php

/*
 *
 * This is an example controller 
 *
 */


$router = new Router();



$router->attach('GET', '/', 'home');
function home() {
    
    echo 'Hello, World.';

}

$router->attach_default('404', 'default_404');
function default_404() {

    echo 'This is a 404 page';

}

$router->attach('GET', '/users/(?P<id>\d+)', 'get_user');
function get_user($id) {

    echo 'Get user {$id}';

}
