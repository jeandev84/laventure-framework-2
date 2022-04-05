<?php
namespace Laventure\Foundation\Generators;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Component\Routing\Resource\WebResource;
use Laventure\Component\Templating\Renderer\Renderer;
use Laventure\Foundation\Application;
use Laventure\Foundation\Loaders\RouteLoader;


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
        * @param string $controller
        * @param array $actions
        * @return bool
      */
      public function generateController(string $controller, array $actions = []): bool
      {
             if (empty($actions)) {
                 $actions = ['index'];
             }

             $controllerParts = explode('/', $controller);
             $controllerClass = end($controllerParts);
             $module = str_replace($controllerClass, '', implode('\\', $controllerParts));

             $controllerStub =  $this->generateStub('resource/controller', [
                  'ControllerNamespace' => $this->getControllerNamespace($module),
                  'ControllerClass'     => $controllerClass,
                  'ControllerActions'   => $this->generateActions($controller, $actions)
             ]);


             return $this->writeTo($this->loadControllerPath($controller), $controllerStub);
      }




      /**
       * Generate controller actions
       *
       * @param string $controller
       * @param array $actions
       * @return string
      */
      public function generateActions(string $controller, array $actions): string
      {
            $actionStubs = [];

            foreach ($actions as $actionName) {

                 $renderPath = $this->generateRenderPath($controller, $actionName);

                 $actionStubs[] = $this->generateStub("resource/action", [
                      "ActionName" => $actionName,
                      "ViewPath"   => sprintf('%s.php', $renderPath)
                 ]);
            }

            return implode("\n\n", $actionStubs);
      }




      /**
       * Generate templates
       *
       * @param string $controller
       * @param array $actions
       * @return void
      */
      public function generateTemplates(string $controller, array $actions)
      {
           foreach ($actions as $actionName) {
               $viewPath = $this->generateRenderPath($controller, $actionName);
               $templatePath = $this->generateTemplatePath($viewPath);
               if($this->generateFile($templatePath)) {
                    $this->generatedTemplates[] = $templatePath;
               }
           }
      }




      /**
       * @param string $controller
       * @param array $actions
       * @return void
      */
      public function generateControllerRoutes(string $controller, array $actions)
      {

      }




      /**
       * @param string $controllerName
       * @return void
      */
      public function makeWebResource(string $controllerName)
      {
            $this->generateController($controllerName, WebResource::getActions());
      }




      public function makeResourceAPI(string $controllerName)
      {

      }



      /**
       * @return array
      */
      public function getGeneratedTemplates(): array
      {
          return $this->generatedTemplates;
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
            $dir = strtolower(str_replace('Controller', '', $controllerName));

            return sprintf('%s/%s', $dir, strtolower($actionName));
      }

}