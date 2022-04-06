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
       * @param bool $isResource
       * @return bool
      */
      public function generateController(string $controller, array $actions = [], bool $isResource = false): bool
      {
             $controllerParts  = explode('/', $controller);
             $controllerClass  = end($controllerParts);
             $module           = str_replace($controllerClass, '', implode('\\', $controllerParts));
             $path             = strtolower(str_replace('Controller', '', $controller));

             $moduleParams = [];

             if ($module) {
                 $modulePath   = strtolower(str_replace('\\', '/', $module));
                 $moduleParams = [
                     'module'    => $module,
                     'path'      => $modulePath,
                     'name'      => str_replace('/', '.', $modulePath)
                 ];
             }

             if (empty($actions)) {
                 $actions = [
                     "index" => [
                        "methods"    => "GET",
                        "path"       => $path = sprintf('%s/index', $path),
                        "action"     => sprintf('%s@index', $controller),
                        "name"       => str_replace('/', '.', $path),
                        "viewPath"   => sprintf('%s.php', $path)
                     ]
                ];
             }


             $controllerStub = $this->generateStub('routing/controller/controller', [
                 'ControllerNamespace' => $this->getControllerNamespace($module),
                 'ControllerClass'     => $controllerClass,
                 'ControllerActions'   => $this->generateActions($actions, $moduleParams, $isResource)
             ]);


             return $this->writeTo($this->loadControllerPath($controller), $controllerStub);
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
       * Generate controller actions
       *
       * @param array $actions
       * @param array $moduleReferences
       * @param bool $isResource
       * @return string
      */
      protected function generateActions(array $actions, array $moduleReferences, bool $isResource): string
      {
            $actionStubs = [];
            $routeGroups = [];
            $module      = $moduleReferences['module'] ?? "";

            foreach ($actions as $actionName => $params) {

                $routeMethod  = $params["methods"];
                $routePath    = $params['path'];
                $routeName    = $params["name"];
                $routeAction  = $params["action"];
                $viewPath     = $params["viewPath"];
                $routePath    = trim(str_replace(['index', 'list'], '', $routePath), '/');

                // Add action stub.
                $actionStubs[] = $this->generateStub("routing/controller/action", [
                    "RouteMethod" => $routeMethod,
                    "RoutePath"   => $routePath,
                    "RouteName"   => $routeName,
                    "ActionName"  => $actionName,
                    "Action"      => $routeAction,
                    "ViewPath"    => $viewPath
                ]);


                // Generate route.
                if (! $isResource) {
                    if ($module) {
                        $routeGroups[] = $this->generateStub('routing/routes/web_routes', [
                            'METHODS' => $routeMethod,
                            'PATH'    => str_replace($moduleReferences['path'], '', $routePath),
                            'ACTION'  => str_replace([trim($moduleReferences['module'], '\\'), '/'], '', $routeAction),
                            'NAME'    => str_replace($moduleReferences['name'], '', $routeName),
                        ]);;

                    } else {
                        $this->generateRoute([
                            'METHODS' => $routeMethod,
                            'PATH'    => $routePath,
                            'ACTION'  => $routeAction,
                            'NAME'    => $routeName
                        ]);
                    }
                }

                // Generate template.
                $this->generateTemplate($viewPath, $routeAction);
            }


            if ($module) {
                $mr = [];
                foreach ($moduleReferences as $key => $reference) {
                    $mr[] = "'$key' => '$reference'";
                }

                $routeGroupStub = $this->generateStub('routing/routes/group/routes', [
                    'Routes'     => implode("\n", $routeGroups),
                    'Attributes' => "[". implode(", ", $mr) . "]"
                ]);

                $this->append('config/routes/web.php', $routeGroupStub);
            }

            return implode("\n\n", $actionStubs);
      }




      /**
       * Generate templates
       *
       * @param string $viewPath
       * @param $routeAction
       * @return bool
      */
      protected function generateTemplate(string $viewPath, $routeAction): bool
      {
           $viewPath = $this->renderer->loadTemplatePath($viewPath);
           $viewPath = str_replace($this->getProjectDir(), '', $viewPath);

           $stub = $this->generateStub('routing/template/view', [
              "Action"    => str_replace('/', '\\', $routeAction),
              "ViewPath"  => $viewPath
           ]);

           return $this->append($viewPath, $stub);
      }




      /**
       * @param array $params
       * @return bool
      */
      protected function generateRoute(array $params): bool
      {
            extract($params);

            $routeMethod  = $params["METHODS"];
            $routePath    = $params["PATH"];
            $routeAction  = $params["ACTION"];
            $routeName    = $params["NAME"];

            $stub = $this->generateStub('routing/routes/web_routes', [
               'METHODS' => $routeMethod,
               'PATH'    => $routePath,
               'ACTION'  => $routeAction,
               'NAME'    => $routeName
           ]);

          return $this->append('config/routes/web.php', $stub);
      }




      /**
        * @param array $params
        * @return array
      */
      protected function makeActionParams(array $params): array
      {
           $actionParams = [];

           foreach ($params as $index => $param) {
               $actionParams[$index] = [
                   "RouteMethod" => $param["methods"],
                   "RoutePath"   => $param["path"],
                   "RouteName"   => $param['name'],
                   "ActionName"  => $param['action'],
                   "ViewPath"    => $param['viewPath']
               ];
           }

           return $actionParams;
      }




      /**
       * @param string|null $module
       * @return string
      */
      protected function getControllerNamespace(string $module = null): string
      {
           return $this->loader->loadControllerNamespace($module);
      }
}