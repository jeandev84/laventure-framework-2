```php 
<?php

use App\Http\Controller\DefaultController;
use Laventure\Component\Routing\Router;


require __DIR__.'/../vendor/autoload.php';


$router = new Router();
$router->namespace("App\\Http\\Controller");
$router->domain('http://localhost:8000/');

/* DEFAULT */
$router->get('', [DefaultController::class, 'index'], 'default');


/*
RESOURCE
$router->resource('users', 'UserController');
*/


$options = [
    'prefix' => '/admin',
    'module' => 'Admin',
    'name'   => 'admin.',
    'middlewares' => [\App\Http\Middleware\IsAdminMiddleware::class]
];

$router->group(function (Router $router) {
    $router->resource('users', 'UserController');
}, $options);


/* GROUP
$attributes = [
    'prefix' => '/admin',
    'module' => 'Admin',
    'name'   => 'admin.',
    'middlewares' => [\App\Http\Middleware\IsAdminMiddleware::class]
];

$router->group(function (Router $router) {
     $router->get('/', 'UserController@list', 'list');
     $router->get('/show/{id}', 'UserController@show', 'show');
     $router->map('GET|POST', '/create', 'UserController@create', 'create');
     $router->map('GET|POST','/{id}/edit', 'UserController@edit', 'edit');
     $router->get('/remove/{id}', 'UserController@remove', 'remove');
}, $attributes);
*/


/*
API
$options = [
    'prefix' => '/api',
    'module' => 'Api',
    'name'   => 'api.'
];

$router->api()->group(function (Router $router) {
    $router->resourceAPI('users', 'UserController');
}, $options);


$router->api(function (Router $router) {
    $router->resourceAPI('users', 'UserController');
});

*/

dump($router->getRoutes());


try {

    $response = $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

} catch (\Laventure\Component\Routing\Exception\NotFoundException $e) {

    dd($e->getMessage());

}



echo $response;

```