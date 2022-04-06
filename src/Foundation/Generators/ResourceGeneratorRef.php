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
     * @param array $resources
     * @return bool
     */
    public function generateController(string $controller, array $actions = [], array $resources = []): bool
    {
        $controllerParts = explode('/', $controller);
        $controllerClass = end($controllerParts);
        $module   = str_replace($controllerClass, '', implode('\\', $controllerParts));
        $viewDir  = strtolower(str_replace('Controller', '', $controller));

        if (empty($actions)) {
            /*
             "methods"    => $methods,
             "module"     => $module,
             "controller" => $controllerClass,
             "action"     => sprintf('%s@%s', $controller, $action),
             "path"       => $this->generateRouteURI($controller, $action),
             "name"       => $this->generateRouteName($controller, $action),
             "viewDir"    => $viewDir
            */
            $actions = $this->makeRouteParams([
                "methods"    => "GET",
                "module"     => $module,
                "controller" => $controllerClass,
                "action"     => "index"
            ]);
        }

        dd($actions);
        /*
          1. php console make:controller Module/Admin/PostController

          "index" => [
               "methods" => "GET"
               "module"  => "Module\Admin\"
               "action"  => "PostController@index"
               "path"    => "post"
               "name"    => "post.index"
           ];

          2. php console make:controller Admin/PostController

          "index" => [
               "methods" => "GET"
               "module"  => "Admin\"
               "action"  => "PostController@index"
               "path"    => "post"
               "name"    => "post.index"
           ]

          3. php console make:controller PostController

          "index" => [
               "methods" => "GET"
               "module"  => ""
               "action"  => "PostController@index"
               "path"    => "post"
               "name"    => "post.index"
          ]

        */

        $collection = $this->getRouter()->getCollection();
        $controllerFullNamespace = "App\\Http\\Controller\\$controllerClass";

        if ($collection->hasController($controllerFullNamespace)) {
            return trigger_error("Controller {$controllerFullNamespace} already registered.");
        }

        $path = strtolower(str_replace('\\', '/', $module));
        $name = strtolower(str_replace('\\', '.', $module));

        $moduleReferences = [
            'module'    => trim($module, '\\'),
            'path'      => trim($path, '/'),
            'name'      => $name
        ];


        $controllerStub =  $this->generateStub('routing/controller/controller', [
            'ControllerNamespace' => $this->getControllerNamespace($module),
            'ControllerClass'     => $controllerClass,
            'ControllerActions'   => $this->generateActions(
                $controller,
                $actions,
                $resources,
                $moduleReferences
            )
        ]);


        return $this->writeTo($this->loadControllerPath($controller), $controllerStub);
    }


    /**
     * Generate controller actions
     *
     * @param string $controller
     * @param array $actions
     * @param array $resourceParams
     * @param array $moduleReferences
     * @return string
     */
    public function generateActions(
        string $controller,
        array $actions = [],
        array $resourceParams = [],
        array $moduleReferences = []
    ): string
    {
        $actionStubs = [];

        dd($moduleReferences);

        $routeGroups = [];

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
                $routeGroups[] = $this->generateRoute([
                    "methods" => $routeMethod,
                    "path"    => $routePath,
                    "action"  => sprintf('%s@%s', $controller, $actionName),
                    "name"    => $this->generateRouteName($controller, $actionName)
                ]);
            }
        }

        if ($moduleReferences) {

            $routeGroupStub = $this->generateStub('routing/routes/group/routes', [
                'Routes' => implode("\n", $routeGroups),
                'Attributes' => $moduleReferences
            ]);

            $this->append('config/routes/web.php', $routeGroupStub);
        }

        if ($resourceParams) {

            $resourceName = $resourceParams["resourceName"];
            $resourceType = $resourceParams["resourceType"];

            if($this->getRouter()->hasResource($resourceName)) {
                return trigger_error("Resource {$resourceType} ( ". ucfirst($resourceName) ." ) already exist.");
            }

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
            dd($viewPath, __METHOD__);
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
        $dir = str_replace('Controller', '', $controllerName);

        return strtolower(sprintf('%s/%s', $dir, $actionName));
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
     * @param string $controller
     * @param string $action
     * @param string|null $module
     * @return string
     */
    private function generateRouteURI(string $controller, string $action, string $module = null): string
    {
        // PostController => post
        // Admin/PostController => admin/post
        // Module/Admin/PostController => module/admin/post

        $path = strtolower(str_replace('Controller', '', $controller));

        if (! \in_array($action, ['index', 'list'])) {
            $path .= '/'. strtolower($action);
        }

        return sprintf('%s%s', $module, $path);
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
     * @param array $params
     * @return array[]
     */
    protected function makeRouteParams(array $params): array
    {
        extract($params);

        $viewDir  = strtolower(str_replace('Controller', '', $controller));

        return [
            $action => [
                "methods"    => $methods,
                "module"     => $module,
                "controller" => $controller,
                "action"     => sprintf('%s@%s', $controller, $action),
                "path"       => $this->generateRouteURI($controller, $action),
                "name"       => $this->generateRouteName($controller, $action),
                "viewDir"    => $viewDir
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