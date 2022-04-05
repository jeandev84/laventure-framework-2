```php  
<?php

use App\Http\Controller\DefaultController;
use Laventure\Component\Routing\Router;


require __DIR__.'/../vendor/autoload.php';


$router = new Router();
$router->namespace("App\\Http\\Controller");


$router->middlewares([
    'auth' => \App\Http\Middleware\AuthenticateMiddleware::class,
    'foo'  => \App\Http\Middleware\FooMiddleware::class
]);



/*
Resolve this case
$router->get('', 'App\Http\Controller\DefaultController::index', 'default');
*/

$router->get('', [DefaultController::class, 'index'], 'default');


/*
$router->get('', function () {
    return "Salut";
}, 'welcome');

$router->get('/profile/{name}', function ($name = null) {
    return "Hi" . ($name ? ", $name" : '');
}, 'profile')->where('name', '[a-z\-0-9]+');
// ->where('name', '\?.+');
//->whereSlug('name');

/*
$router->get('/user/{id?}', function ($id = null) {
    return "Identify" . ($id ? ", $id" : '');
}, 'profile');
*/


$attributes = [
    'prefix' => '/admin',
    'module' => 'Admin',
    'name'   => 'admin.',
    'middlewares' => ['auth', 'foo', \App\Http\Middleware\IsAdminMiddleware::class],
    // 'middlewares' => [\App\Http\Middleware\FooMiddleware::class, \App\Http\Middleware\AuthenticateMiddleware::class]
];


$router->group(function (Router $router) {
     $router->get('/', 'UserController@list', 'list');
     $router->get('/show/{id}', 'UserController@show', 'show');
     $router->map('GET|POST', '/create', 'UserController@create', 'create');
     $router->map('GET|POST','/{id}/edit', 'UserController@edit', 'edit');
     $router->get('/remove/{id}', 'UserController@remove', 'remove');
}, $attributes);



$router->get('/foo', [\App\Http\Controller\FooController::class, 'index'], 'foo')
       ->middlewares(\App\Http\Middleware\FooMiddleware::class);


dump($router->getRoutes());


try {

    $response = $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

} catch (\Laventure\Component\Routing\Exception\NotFoundException $e) {

    dd($e->getMessage());

}



echo $response;
```