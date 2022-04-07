<?php
namespace Laventure\Foundation\Generators;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Routing\Resource\ApiResource;
use Laventure\Component\Routing\Resource\WebResource;
use Laventure\Component\Templating\Renderer\Renderer;
use Laventure\Foundation\Application;
use Laventure\Foundation\Loaders\RouteLoader;
use Laventure\Component\Routing\Router;


/**
 * @ResourceGenerator
*/
class ResourceGenerator extends StubGenerator
{


      /**
       * @var RouteLoader
      */
      protected $loader;




      /**
       * @var Renderer
      */
      protected $renderer;




      /**
       * @var Router
      */
      protected $router;



      /**
       * @param Application $app
       * @param FileSystem $fileSystem
       * @param RouteLoader $loader
       * @param Renderer $renderer
      */
      public function __construct(
          Application $app,
          FileSystem $fileSystem,
          RouteLoader $loader,
          Renderer $renderer
      )
      {
          parent::__construct($app, $fileSystem);
          $this->loader   = $loader;
          $this->renderer = $renderer;
          $this->router   = $loader->getRouter();
      }




      /**
       * @return Router
      */
      public function getRouter(): Router
      {
           return $this->router;
      }




      /**
       * @param string|null $module
       * @return string
      */
      protected function getControllerNamespace(string $module = null): string
      {
            return $this->loader->loadControllerNamespace($module);
      }




      /**
       * @param string $controller
       * @return array
      */
      protected function makeControllerParts(string $controller): array
      {
           return explode('/', $controller);
      }




      /**
       * @param string $controller
       * @return false|mixed|string
      */
      protected function getControllerClass(string $controller)
      {
           $parts = $this->makeControllerParts($controller);

           return end($parts);
      }




     /**
      * @param string $controllerPath
      * @return string
     */
     protected function transformEntry(string $controllerPath): string
     {
          return strtolower(str_replace('Controller', '', $controllerPath));
     }




    /**
     * @param string $controller
     * @return array|string|string[]
    */
    protected function getModule(string $controller)
    {
        $controllerClass = $this->getControllerClass($controller);

        $parts = $this->makeControllerParts($controller);

        $joinedParts = implode('/', $parts);

        return str_replace(['/', $controllerClass], ['\\', ''], $joinedParts);
    }





    /**
     * @param string $controller
     * @param string $action
     * @return string
    */
    protected function makeRoutePath(string $controller, string $action): string
    {
         $path = sprintf('%s/%s', $this->transformEntry($controller), $action);

         return trim(str_replace(['index', 'list'], '', $path), '/');
    }





    /**
     * @param string $controller
     * @param string $action
     * @return string
    */
    protected function makeRouteViewPath(string $controller, string $action): string
    {
         return sprintf('%s/%s.php', $this->transformEntry($controller), $action);
    }





    /**
     * @param string $controller
     * @param string $action
     * @return string
    */
    protected function makeRouteAction(string $controller, string $action): string
    {
          $controllerClass = $this->getControllerClass($controller);

          return sprintf('%s@%s', $controllerClass, $action);
    }




    /**
     * @param string $controller
     * @param string $action
     * @return string
    */
    protected function makeRouteName(string $controller, string $action): string
    {
         $name = str_replace('/', '.', $this->transformEntry($controller));

         return sprintf('%s.%s', $name, $action);
    }




    /**
     * @param string $module
     * @return string
    */
    protected function makeModuleName(string $module): string
    {
         return strtolower(str_replace('/', '.', $this->makeModulePath($module)));
    }




    /**
     * @param string $module
     * @return string
    */
    protected function makeModulePath(string $module): string
    {
         return strtolower(str_replace('\\', '/', $module));
    }




    /**
     * @param string $controllerName
     * @return string
    */
    protected function loadControllerPath(string $controllerName): string
    {
         return $this->loader->generateControllerPath($controllerName);
    }





    /**
     * @param string $controller
     * @return array[]
    */
    protected function makeDefaultActionParams(string $controller): array
    {
          return [
            'index' => [
                'methods'  => 'GET',
                'path'     => $this->makeRoutePath($controller, 'index'),
                'action'   => $this->makeRouteAction($controller, 'index'),
                'name'     => $this->makeRouteName($controller, 'index'),
                'viewPath' => $this->makeRouteViewPath($controller, 'index')
            ]
         ];
    }




    /**
     * @param string $module
     * @return array
    */
    protected function makeModuleAttributes(string $module): array
    {
          return [
              "module"   => sprintf('%s\\', $module),
              "prefix"   => $this->makeModulePath($module),
              "name"     => $this->makeModuleName($module),
          ];
    }






    /**
     * @param string $entityClass
     * @param string $resourceType
     * @return bool
    */
    protected function generateResourceFromEntityClass(string $entityClass, string $resourceType = 'web'): bool
    {
        $controllerName = sprintf('%sController', $entityClass);
        $resourceName   = strtolower($entityClass);

        return $this->generateResource($controllerName, $resourceName, $resourceType);
    }



    /**
     * Determine if the given controller exist in collection
     *
     * @param string $controller
     * @return string
    */
    protected function getControllerWithNamespace(string $controller): string
    {
        $controllerRelated = str_replace('/', '\\', $controller);
        return $this->router->getController($controllerRelated);
    }





    /**
     * @param string $controller
     * @param array $actions
     * @param bool $isResource
     * @return bool
    */
    public function generateController(string $controller, array $actions = [], bool $isResource = false): bool
    {
         $controllerClass  = $this->getControllerClass($controller);
         $module           = $this->getModule($controller);

         if (empty($actions)) {
             $actions = $this->makeDefaultActionParams($controller);
         }

         $controllerFullNamespace = $this->getControllerWithNamespace($controller);

         if ($this->router->hasController($controllerFullNamespace)) {
              return trigger_error("Controller {$controllerFullNamespace} already generated.");
         }

         $controllerStub = $this->generateStub('routing/controller/controller', [
            'ControllerNamespace' => $this->getControllerNamespace($module),
            'ControllerClass'     => $controllerClass,
            'ControllerActions'   => $this->generateActions($actions)
         ]);



         // generate routes and templates
         if ($actions) {
              $this->generateRoutes($actions, $module, $isResource);
              $this->generateTemplates($actions);
         }

         return $this->writeTo($this->loadControllerPath($controller), $controllerStub);
    }


    /**
     * Generate controller actions
     *
     * @param array $actions
     * @return string
    */
    protected function generateActions(array $actions): string
    {
        $actionStubs = [];

        foreach ($actions as $action => $params) {
            $actionStubs[] = $this->generateActionStub($action, $params);
        }

        return implode("\n\n", $actionStubs);
    }




    /**
     * @param array $actions
     * @param string|null $module
     * @param bool $isResource
     * @return void
    */
    public function generateRoutes(array $actions, string $module = null, bool $isResource = false)
    {
          $stubRouteGroup  = [];
          $actions = array_values($actions);

          foreach ($actions as $params) {
              if ($module) {
                  $stubRouteGroup[] = $this->generateRouteStub($params, $module);
              }else {
                  $this->generateWebRoute($params);
              }
          }

          if ($module) {
              $this->generateRouteGroup([
                  'attributes' => $this->makeModuleAttributes($module),
                  'routes'     => $stubRouteGroup
              ]);
          }
    }




    /**
     * @param array $params
     * @param string|null $module
     * @return string|string[]
     */
    public function generateRouteStub(array $params, string $module = null)
    {
        $modulePath = $this->makeModulePath($module);
        $moduleName = $this->makeModuleName($module);

        $path = str_replace($modulePath, '', $params['path']);
        $name = str_replace($moduleName, '', $params['name']);

        return $this->generateStub('routing/routes/types/map', [
            'METHODS' => $params['methods'],
            'PATH'    => $path,
            'ACTION'  => $params['action'],
            'NAME'    => $name,
        ]);
    }




    /**
     * @param array $params
     * @param string $type
     * @return bool
    */
    public function generateRouteGroup(array $params, string $type = 'web'): bool
    {
        $mr = [];

        foreach ($params['attributes'] as $key => $value) {
            $mr[] = sprintf('"%s" => "%s"', $key, $value);
        }

        $attributes = "[". implode(", ", $mr) . "]";
        $variable = trim($params['attributes']['name'], '.');
        $variable = str_replace('.', '_', $variable);

        $stub = $this->generateStub("routing/routes/group", [
            'VARIABLE'     => $variable,
            'ATTRIBUTES'   => $attributes,
            'ROUTES'       => implode("\n", $params['routes'])
        ]);

        // dd($stub);

        return $this->append("config/routes/{$type}.php", $stub);
    }





    /**
     * @param string $controller
     * @return bool
    */
    public function generateControllerResourceWeb(string $controller): bool
    {
         $controllerClass = $this->getControllerClass($controller);
         $resourceName    = strtolower($controllerClass);

         return $this->generateResource($resourceName, $controllerClass);
    }




    /**
     * @param string $controller
     * @return bool
    */
    public function generateControllerResourceAPI(string $controller): bool
    {
         $controllerClass = $this->getControllerClass($controller);
         $resourceName    = strtolower($controllerClass);

         return $this->generateResource($resourceName, $controllerClass, 'api');
    }



    /**
     * @param string $entityClass
     * @return bool
    */
    public function generateResourceWebFromEntityClass(string $entityClass): bool
    {
           return $this->generateResourceFromEntityClass($entityClass);
    }





    /**
     * @param string $entityClass
     * @return bool
    */
    public function generateResourceAPIFromEntityClass(string $entityClass): bool
    {
         return $this->generateResourceFromEntityClass($entityClass, 'api');
    }




    /**
     * @param string $resourceName
     * @param string $controllerName
     * @param string $type
     * @return bool
    */
    public function generateResource(string $resourceName, string $controllerName, string $type = 'web'): bool
    {
        $stub = $this->generateStub("routing/routes/resource/{$type}", [
            'ResourceName'       => $resourceName,
            'ResourceController' => $controllerName
        ]);

        $resource = new WebResource($resourceName, $controllerName);

        if ($type === 'api') {
            $resource = new ApiResource($resourceName, $controllerName);
        }

        $generated = $this->generateController($controllerName, $resource->getParams(), true);

        return $generated && $this->append("config/routes/{$type}.php", $stub);
    }





    /**
     * @param string $controllerPath
     * @return string
    */
    protected function generateName(string $controllerPath): string
    {
        $controllerPath = str_replace(['Controller', '/'], ['', '.'], $controllerPath);

        return strtolower($controllerPath);
    }





    /**
     * @param array $params
     * @param string $type
     * @return bool
    */
    private function generateRoute(array $params, string $type): bool
    {
        $stub = $this->generateStub("routing/routes/{$type}", [
            'METHODS' => $params['methods'],
            'PATH'    => $params['path'],
            'ACTION'  => $params['action'],
            'NAME'    => $params['name']
        ]);

        return $this->append("config/routes/{$type}.php", $stub);
    }




    /**
     * Generate simple route to config/routes/api.php
     * Route::map('GET', '/', "FooController@index", 'foo.index');
     *
     * @param array $params
     * @return bool
    */
    public function generateApiRoute(array $params): bool
    {
        return $this->generateRoute($params, 'api');
    }




    /**
     * Generate simple route to config/routes/web.php
     * Route::map('GET', '/', "FooController@index", 'foo.index');
     *
     * @param array $params
     * @return bool
    */
    public function generateWebRoute(array $params): bool
    {
         return $this->generateRoute($params, 'web');
    }




    /**
     * @param string $actionName
     * @param array $params
     * @return string|string[]
    */
    protected function generateActionStub(string $actionName, array $params)
    {
        return $this->generateStub("routing/controller/action", [
             "RouteMethod" => $params["methods"],
             "RoutePath"   => $params['path'],
             "RouteName"   => $params["name"],
             "ActionName"  => $actionName,
             "Action"      => $params["action"],
             "ViewPath"    => $params["viewPath"]
        ]);
    }




    /**
     * Generate templates
     *
     * @param array $actions
     * @return void
    */
    protected function generateTemplates(array $actions)
    {
         $actions = array_values($actions);

         foreach ($actions as $param) {
             $this->generateTemplate($param['viewPath'], $param['action']);
         }
    }




    /**
     * @param string $viewPath
     * @param string $action
     * @return bool
    */
    public function generateTemplate(string $viewPath, string $action): bool
    {
        $viewPath = $this->renderer->loadTemplatePath($viewPath);
        $viewPath = str_replace($this->getProjectDir(), '', $viewPath);

        $stub = $this->generateStub('routing/template/view', [
            "Action"    => $action,
            "ViewPath"  => $viewPath
        ]);

        return $this->append($viewPath, $stub);
    }

}