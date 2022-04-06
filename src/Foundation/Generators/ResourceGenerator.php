<?php
namespace Laventure\Foundation\Generators;


use Laventure\Component\FileSystem\FileSystem;
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
       * @var array
      */
      protected $generatedTemplates = [];




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
      }




      /**
       * @return Router
      */
      public function getRouter(): Router
      {
           return $this->loader->getRouter();
      }



      /**
       * @param string $controller
       * @param array $actions
       * @param array $resources
       * @return bool
      */
      public function generateController(string $controller, array $actions = [], array $resources = []): bool
      {
             $controllerParts = explode('/', $controller);
             $controllerClass = end($controllerParts);
             $module = str_replace($controllerClass, '', implode('\\', $controllerParts));

             if (empty($actions)) {
                $actions = $this->getDefaultRouteActionParams($controllerClass);
             }


             $collection = $this->getRouter()->getCollection();
             $controllerFullNamespace = "App\\Http\\Controller\\$controllerClass";

             if ($collection->hasController($controllerFullNamespace)) {
                 return trigger_error("Controller {$controllerFullNamespace} already exist.");
             }

             $controllerStub =  $this->generateStub('routing/controller/controller', [
                  'ControllerNamespace' => $this->getControllerNamespace($module),
                  'ControllerClass'     => $controllerClass,
                  'ControllerActions'   => $this->generateActions($controller, $actions, $resources)
             ]);


             return $this->writeTo($this->loadControllerPath($controller), $controllerStub);
      }





      /**
       * @param string $controller
       * @param array $actions
       * @param array $params
       * @return void
      */
      public function generateControllerAndRoute(string $controller, array $actions = [], array $params = [])
      {
             if ($this->generateController($controller, $actions)) {

             }
      }


      /**
        * Generate controller actions
        *
        * @param string $controller
        * @param array $actions
        * @param array $resourceParams
        * @return string
      */
      public function generateActions(string $controller, array $actions = [], array $resourceParams = []): string
      {
            $actionStubs = [];

            foreach ($actions as $actionName => $params) {

                 $renderPath = $this->generateRenderPath($controller, $actionName);

                 $routeMethod = $params[0] ?? "";
                 $routePath   = $params[1] ?? "";
                 $routeName   = $params[2] ?? "";

                 $actionStubs[] = $this->generateStub("routing/controller/action", [
                      "RouteMethod" => $routeMethod,
                      "RoutePath"   => $routePath,
                      "RouteName"   => $routeName,
                      "ActionName"  => $actionName,
                      "ViewPath"    => sprintf('%s.php', $renderPath)
                 ]);


                 if (! $resourceParams) {
                     $this->generateRoute([
                         "methods" => $routeMethod,
                         "path"    => $routePath,
                         "action"  => sprintf('%s@%s', $controller, $actionName),
                         "name"    => $this->generateRouteName($controller, $actionName)
                     ]);
                 }
            }

            if ($resourceParams) {

                $resourceName = $resourceParams["resourceName"];
                $resourceType = $resourceParams["resourceType"];

                switch ($resourceType) {
                    case "web":
                        $this->generateRoutesResourceWeb($resourceName, $controller);
                        break;
                    case "api":
                        $this->generateRoutesResourceAPI($resourceName, $controller);
                        break;
                }
            }

            $this->generateTemplates($controller, $actions);

            return implode("\n\n", $actionStubs);
      }





      /**
        * Generate templates
        *
        * @param string $controllerName
        * @param array $actions
        * @return void
      */
      public function generateTemplates(string $controllerName, array $actions)
      {
           $actions = array_keys($actions);

           foreach ($actions as $actionName) {

               $viewPath     = $this->generateRenderPath($controllerName, $actionName);
               $templatePath = $this->generateTemplatePath($viewPath);

               $stub = $this->generateStub('routing/template/view', [
                    "ControllerName" => $controllerName,
                    "ActionName"     => $actionName,
                    "ViewPath"       => $templatePath
               ]);

               $this->append($templatePath, $stub);
           }
      }




      /**
       * @param string $controllerName
       * @param string|null $resourceName
       * @return bool
      */
      public function generateControllerResourceWeb(string $controllerName, string $resourceName = null): bool
      {
            if (! $resourceName) {
                $resourceName = $this->generateName($controllerName);
            }

            $resource = new WebResource($resourceName, $controllerName);
            $actions = $resource->getFilteredRouteParams();

            if($this->getRouter()->hasResource($resourceName)) {
                 return trigger_error("Resource {$resourceName} already exist.");
            }

            return $this->generateController($controllerName, $actions, [
                "resourceName" => $resourceName,
                "resourceType" => "web"
            ]);
      }




      /**
        * @param string $resourceName
        * @param string $controllerName
        * @return bool
      */
      protected function generateRoutesResourceWeb(string $resourceName, string $controllerName): bool
      {
            $stub = $this->generateStub('routing/routes/resource/web_routes', [
                 'ResourceName'       => $resourceName,
                 'ResourceController' => $controllerName
            ]);

            return $this->append('config/routes/web.php', $stub);
      }


      /**
       * @param string $entityClass
       * @return bool
      */
      public function generateResourceWeb(string $entityClass): bool
      {
             $controllerName = sprintf('%sController', $entityClass);
             $resourceName   = strtolower($entityClass);

             return $this->generateControllerResourceWeb($controllerName, $resourceName);
      }




      /**
       * @param string $resourceName
       * @param string $controllerName
       * @return bool
      */
      protected function generateRoutesResourceAPI(string $resourceName, string $controllerName): bool
      {
            $stub = $this->generateStub('routing/routes/resource/api_routes', [
               'ResourceName'       => $resourceName,
               'ResourceController' => $controllerName
            ]);

            return $this->append('config/routes/api.php', $stub);
      }





      /**
       * @param string $viewPath
       * @return array|string|string[]
      */
      public function generateTemplatePath(string $viewPath)
      {
           $path = $this->renderer->loadTemplatePath($viewPath);

           return str_replace($this->getProjectDir(), '', $path);
      }






      /**
       * @param string|null $module
       * @return string
      */
      public function getControllerNamespace(string $module = null): string
      {
            return $this->loader->loadControllerNamespace($module);
      }




      /**
       * @param string $controllerName
       * @return string
      */
      public function loadControllerPath(string $controllerName): string
      {
          return $this->loader->generateControllerPath($controllerName);
      }




      /**
       * @param string $controllerName
       * @param string $actionName
       * @return string
      */
      protected function generateRenderPath(string $controllerName, string $actionName): string
      {
            $dir = $this->generateName($controllerName);

            return sprintf('%s/%s', $dir, strtolower($actionName));
      }




      /**
       * @param string $controllerName
       * @return string
      */
      protected function generateName(string $controllerName): string
      {
          $controllerName = str_replace(['Controller', '/'], ['', '.'], $controllerName);

          return strtolower($controllerName);
      }




      /**
       * @param string $controllerName
       * @param string $actionName
       * @return string
      */
      private function generateRouteURI(string $controllerName, string $actionName): string
      {
          $path = $this->generateName($controllerName);

          if (! \in_array($actionName, ['index', 'list'])) {
               $path .= '/'. strtolower($actionName);
          }

          return $path;
      }



      /**
       * @param string $controllerName
       * @param string $actionName
       * @return string
      */
      private function generateRouteName(string $controllerName, string $actionName): string
      {
           return sprintf('%s.%s', $this->generateName($controllerName), $actionName);
      }




      /**
       * @param string $controllerClass
       * @return array[]
      */
      private function getDefaultRouteActionParams(string $controllerClass): array
      {
           return [
              'index' => [
                  'GET',
                  $this->generateRouteURI($controllerClass, 'index'),
                  $this->generateRouteName($controllerClass, 'index')
              ]
           ];
      }




      /**
        * @param array $params
        * @return array
      */
      protected function getRouteParams(array $params): array
      {
           return [
               'methods' => $params[0] ?? "",
               'path'    => $params[1] ?? "",
               'action'  => $params[2] ?? "",
               'name'    => $params[3] ?? ""
           ];
      }



      /**
       * @param array $params
       * @return bool
      */
      protected function generateRoute(array $params): bool
      {
            $stub = $this->generateStub('routing/routes/web_routes', [
                'METHODS' => $params["methods"] ?? "",
                'PATH'    => $params["path"] ?? "",
                'ACTION'  => $params["action"] ?? "",
                'NAME'    => $params["name"] ?? ""
            ]);

            return $this->append('config/routes/web.php', $stub);
      }

}