### Router

- Example 1
```php 
require __DIR__.'/../vendor/autoload.php';


$router = new \Laventure\Component\Routing\Router();
$router->namespace("App\\Http\\Controller");

$router//->module('Admin')
       ->name('admin.')
       ->middleware([\App\Http\Middleware\FooMiddleware::class])
;


$router->get('/', "SiteController@index", 'homepage');
$router->get('/about', "SiteController@about", 'about');
$router->map("GET|POST", '/contact', "SiteController@contact", 'contact');

$router->get('/welcome/{name}', "SiteController@welcome", 'welcome')
       ->whereText('name');
       
$router->get('/foo/{name?}', "SiteController@foo", 'foo')
       ->whereText('name');
       
$router->get('/arithmetic/{id}', "SiteController@arithmetic", 'arithmetic')
       ->whereNumeric('id');
       
       
$router->get('/auth/login', "Userontroller@login", 'login');

$router->get('/auth/back',  function () {
   return "Auth back";
}, 'back');





dump($router->getRoutes());


try {

    $route = $router->dispatchRoute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

} catch (\Laventure\Component\Routing\Exception\NotFoundException $e) {

    dd($e->getMessage());

}


$target = $route->getTarget();

if (! is_callable($target)) {
    $controllerClass = $route->getOption("@controller");
    $target = [new $controllerClass(), $route->getOption("@action")];
}

$response = call_user_func_array($target, $route->getMatches());

echo $response;
```