<?php
namespace Laventure\Foundation\Generators;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Routing\Resource\ApiResource;
use Laventure\Component\Routing\Resource\Common\AbstractResource;
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
           return explode(DIRECTORY_SEPARATOR, $controller);
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

        $joinedParts = implode(DIRECTORY_SEPARATOR, $parts);

        return str_replace([DIRECTORY_SEPARATOR, $controllerClass], ['\\', ''], $joinedParts);
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
         return sprintf('%s%s%s.php',
      $this->transformEntry($controller),
             DIRECTORY_SEPARATOR,
             $action
         );
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
         $name = str_replace(DIRECTORY_SEPARATOR, '.', $this->transformEntry($controller));

         return sprintf('%s.%s', $name, $action);
    }




    /**
     * @param string $module
     * @return string
    */
    protected function makeModuleName(string $module): string
    {
         return strtolower(str_replace(DIRECTORY_SEPARATOR, '.', $this->makeModulePath($module)));
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
        $controllerRelated = str_replace(DIRECTORY_SEPARATOR, '\\', $controller);
        return $this->router->getController($controllerRelated);
    }





    /**
     * @param string $controller
     * @param array $actions
     * @param string|null $resourceType
     * @return bool
    */
    public function generateController(string $controller, array $actions = [], string $resourceType = 'web'): bool
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

         $controllerStub = $this->generateStub('routing/controller/blank', [
            'ControllerNamespace' => $this->getControllerNamespace($module),
            'ControllerClass'     => $controllerClass,
            'ControllerActions'   => $this->generateActions($actions, $resourceType)
         ]);


         // generate routes and templates
         if ($actions) {
             if ($resourceType === 'api') {
                   // $this->generateApiRoutes($actions);
             } else {
                 $this->generateWebRoutes($actions, $module);
                 $this->generateTemplates($actions);
             }
         }

         return $this->writeTo($this->loadControllerPath($controller), $controllerStub);
    }




    /**
     * @param array $resourceName
     * @param $controllerName
     * @return bool
    */
    protected function generateApiRoutes(array $resourceName, $controllerName): bool
    {
         $stub = $this->generateStub("routing/routes/resource/api", [
           'ResourceName'       => $resourceName,
           'ResourceController' => $this->getControllerClass($controllerName)
         ]);

         return $this->append("config/routes/api.php", $stub);
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
     * Generate controller actions
     *
     * @param array $actions
     * @param string $resourceType
     * @return string
    */
    protected function generateActions(array $actions, string $resourceType): string
    {
         $actionStubs = [];

         foreach ($actions as $action => $params) {
            if ($resourceType === 'api') {
               $actionStubs[] = $this->generateAPIActionStub($action, $params);
            } else {
                $actionStubs[] = $this->generateWebActionStub($action, $params);
            }
         }

         return implode("\n\n", $actionStubs);
    }





    /**
     * @param array $actions
     * @param string|null $module
     * @return void
    */
    public function generateWebRoutes(array $actions, string $module = null)
    {
          $stubRouteGroup  = [];
          $actions         = array_values($actions);

          foreach ($actions as $params) {
              if ($module) {
                  $stubRouteGroup[] = $this->generateRouteStub($params, $module);
              }else {
                  $this->generateWebRoute($params);
              }
          }

          if ($module) {
              $this->generateWebRouteGroup([
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
     * @return bool
    */
    public function generateWebRouteGroup(array $params): bool
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


        return $this->append("config/routes/web.php", $stub);
    }





    /**
     * @param string $controller
     * @return bool
    */
    public function generateControllerResourceWeb(string $controller): bool
    {
         $resourceName = strtolower(str_replace(['Controller', DIRECTORY_SEPARATOR], ['', '_'], $controller));
         return $this->generateResource($resourceName, $controller);
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
     * @param string $resourceType
     * @return bool
    */
    public function generateResource(string $resourceName, string $controllerName, string $resourceType = 'web'): bool
    {
        $resource = new WebResource($resourceName, $controllerName);

        if ($resourceType === 'api') {
            $resource = new ApiResource($resourceName, $controllerName);
        }

        $resourceParams = $this->transformResourceParams($resource);

        return $this->generateController($controllerName, $resourceParams, $resourceType);
    }




    /**
     * @param AbstractResource $resource
     * @return array
    */
    protected function transformResourceParams(AbstractResource $resource): array
    {
         $items = [];

         foreach ($resource->getParams() as $action => $params) {
              $actionParts        = explode('/', $params['action']);
              $params['path']     = str_replace(['-', '_', '.'], '/', $params['path']);
              $params['viewPath'] = $this->makeRouteViewPath($resource->getController(), $action);
              $params['action']   = end($actionParts);
              $items[$action]     = $params;
         }

         return $items;
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
    private function generateRoute(array $params, string $type = 'web'): bool
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
     * Generate simple route to config/routes/web.php
     * Route::map('GET', '/', "FooController@index", 'foo.index');
     *
     * @param array $params
     * @return bool
    */
    public function generateWebRoute(array $params): bool
    {
         return $this->generateRoute($params);
    }




    /**
     * @param string $actionName
     * @param array $params
     * @return string|string[]
    */
    protected function generateWebActionStub(string $actionName, array $params)
    {
        return $this->generateStub("routing/controller/web/action", [
             "RouteMethod" => $params["methods"],
             "RoutePath"   => $params['path'],
             "RouteName"   => $params["name"],
             "ActionName"  => $actionName,
             "Action"      => $params["action"],
             "ViewPath"    => $params["viewPath"]
        ]);
    }



    /**
     * @param string $actionName
     * @param array $params
     * @return string|string[]
    */
    protected function generateAPIActionStub(string $actionName, array $params)
    {
         return $this->generateStub("routing/controller/web/action", [
             "RouteMethod" => $params["methods"],
             "RoutePath"   => $params['path'],
             "RouteName"   => $params["name"],
             "ActionName"  => $actionName,
             "Action"      => $params["action"]
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

        return $this->append($viewPath, $stub, false);
    }

}