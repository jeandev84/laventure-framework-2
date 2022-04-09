<?php
use Laventure\Component\Container\Container;
use Laventure\Component\Http\Response\RedirectResponse;
use Laventure\Component\Http\Response\Response;
use Laventure\Component\Templating\Renderer\RenderLayoutInterface;


/*
|------------------------------------------------------------------
|   Get application
|------------------------------------------------------------------
*/

if(! function_exists('app')) {

    /**
     * @param string|null $abstract
     * @param array $parameters
     * @return Container
     * @throws
    */
    function app(string $abstract = null, array $parameters = []): Container
    {
        $app = Container::getInstance();

        if(is_null($abstract)) {
            return $app;
        }

        return $app->make($abstract, $parameters);
    }
}



/*
|------------------------------------------------------------------
|   Get base path using in template
|------------------------------------------------------------------
*/

if(! function_exists('base_path')) {

    /**
     * Base Path
     * @param string $path
     * @return string
     * @throws
     * @throws Exception
     */
    function base_path(string $path = ''): string
    {
        return app()->get('path') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}



/*
|------------------------------------------------------------------
|   Get application URL
|------------------------------------------------------------------
*/

if (! function_exists('url')) {

    function url($path, $params = []): string
    {
        return app()->get('router')->url($path, $params);
    }
}



/*
|------------------------------------------------------------------
|   Get application BASE URL
|------------------------------------------------------------------
*/

if (! function_exists('baseURL')) {

    /**
     * @return string
    */
    function baseURL(): string
    {
        return app()->get('request')->baseURL();
    }
}




/*
|------------------------------------------------------------------
|   Get configuration param
|------------------------------------------------------------------
*/

if(! function_exists('config')) {

    /**
     * Config
     * @param string $key
     * @return mixed
    */
    function config(string $key)
    {
        return app()->get('config')->get($key);
    }
}




/*
|------------------------------------------------------------------
|   Get environment param
|   env('SECRET_KEY', 'some_hash')
|------------------------------------------------------------------
*/

if(! function_exists('env'))
{
    /**
     * Get item from environment or default value
     *
     * @param $key
     * @param null $default
     * @return array|string|null
     */
    function env($key, $default = null)
    {
        $value = getenv($key);

        if(! $value) {
            return $default;
        }

        return $value;
    }
}



/*
|------------------------------------------------------------------
|   Get application name
|   Example : <title>app_name()</title>              => Laventure
|   Example : <title>app_name('| Shopping')</title>  => Laventure | Shopping
|------------------------------------------------------------------
*/

if(! function_exists('app_name')) {

    /**
     * Application name
     * @param string $suffix
     * @return string
    */
    function app_name(string $suffix = ''): string
    {
        return ucfirst(\config('app.name')) . $suffix;
    }
}


/*
|------------------------------------------------------------------
|   Generate route by given name
|   Example : route('home') / route('user.show', ['id' => $id]) / route('user.list');
|------------------------------------------------------------------
*/

if(! function_exists('route')) {

    /**
     * @param string $name
     * @param array $params
     * @return string
     * @throws
     * @throws Exception
    */
    function route(string $name, array $params = []): string
    {
        return \app()->get('router')->generate($name, $params);
    }
}



/*
|------------------------------------------------------------------
|   Create a response
|
|   Example : response();
|------------------------------------------------------------------
*/

if(! function_exists('response'))
{

    /**
     * @param string $content
     * @param int $code
     * @param array $headers
     * @return Response
     */
    function response(string $content = '', int $code = 200, array $headers = []): Response
    {
          return new Response($content, $code, $headers);
    }
}



/*
|------------------------------------------------------------------
|   Redirect to path
|
|   Example : redirect()->to('/user');
|
|------------------------------------------------------------------
*/

if(! function_exists('redirect'))
{

    /**
     * @param string $path
     * @param int $code
     * @return RedirectResponse
     */
    function redirect(string $path, int $code = 301): RedirectResponse
    {
        return new RedirectResponse($path, $code);
    }
}



/*
|------------------------------------------------------------------
|   Create a view response
|
|   Example : view('cart/add.php', ['item1' => 'value1', 'item2' => 'value2');
|
|------------------------------------------------------------------
*/

if(! function_exists('view'))
{

    /**
     * @param string $template
     * @param array $data
     * @return Response
    */
    function view(string $template, array $data = []): Response
    {
        $render = app()->get('view');

        if ($render instanceof RenderLayoutInterface) {
             $render->withLayout(app()->get('@layout'));
        }

        $content = $render->render($template, $data);

        return new Response($content, 200, []);
    }
}



/*
|------------------------------------------------------------------
|   Generate asset path
|
|   Example : asset('/css/app.css')
|   Example : asset('/css/app.js')
|   Example : asset('/uploads/thumbs/some_hash.jpg')
|
|------------------------------------------------------------------
*/

if(! function_exists('asset'))
{

    /**
     * @param string|null $path
     * @return string
    */
    function asset(string $path): string
    {
        $asset = app()['assets']->setBaseURL(baseURL());

        return $asset->url($path);
    }
}


/*
|------------------------------------------------------------------
|   Render assets template
|
|   Example : assets(['/css/app.css', '/css/bootstrap/bootstrap.min.css'])
|   Example : assets(['/js/app.js', '/js/bootstrap/bootstrap.min.js', 'js/jquery/jquery.min.js'])
|   Example : assets() generate all available scripts and styles
|
|------------------------------------------------------------------
*/

if(! function_exists('assets'))
{

    /**
     * @param array $files
     * @return string
    */
    function assets(array $files = []): string
    {
        $asset = app()['assets']->setBaseURL(baseURL());

        return $asset->renderTemplates($files);
    }
}



/*
|------------------------------------------------------------------
|   Include path in template
|
|   Example : {{ includePath('/partials/menu/navbar.php') ) }}
|
|------------------------------------------------------------------
*/


if(! function_exists('includePath'))
{

    /**
     * @param string $path
     * @return void
    */
    function includePath(string $path)
    {
        @require app()->get('view')->loadPath($path);
    }
}