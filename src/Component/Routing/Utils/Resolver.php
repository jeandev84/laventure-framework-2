<?php
namespace Laventure\Component\Routing\Utils;



use Closure;
use Laventure\Component\Routing\Group\RouteGroup;


/**
 * @Resolver
*/
class Resolver
{

       /**
        * @var RouteGroup
       */
       protected $routeGroup;




       /**
        * @var string
       */
       protected $namespace;



       /**
        * RouteParameter constructor
       */
       public function __construct()
       {
             $this->setGroup(new RouteGroup());
       }




       /**
         * @param string $namespace
         * @return void
       */
       public function namespace(string $namespace)
       {
            $this->namespace = trim($namespace, '\\');
       }




       /**
        * Set route group
        *
        * @param RouteGroup $routeGroup
        * @return $this
       */
       public function setGroup(RouteGroup $routeGroup): self
       {
            $this->routeGroup = $routeGroup;

            return $this;
       }





       /**
        * @param array $attributes
        * @return void
       */
       public function withAttributes(array $attributes)
       {
             $this->routeGroup->withAttributes($attributes);
       }



       /**
        * Get route group
        *
        * @return RouteGroup
       */
       public function getGroup(): RouteGroup
       {
           return $this->routeGroup;
       }




       /**
         * Resolve methods
         * @param $methods
         * @return array
       */
       public function resolveMethods($methods): array
       {
           if (\is_string($methods)) {
               $methods = explode('|', $methods);
           }

           return (array) $methods;
       }



      /**
       * Resolve path
       *
       * @param $path
       * @return string
      */
      public function resolvePath($path): string
      {
          if ($prefix = $this->routeGroup->getPrefix()) {
              $path = trim($prefix, '/'). '/'. ltrim($path, '/');
          }

          return $path;
      }




      /**
       * @return string
      */
      public function getModule(): string
      {
           return $this->getGroup()->getModule();
      }




      /**
        * @return string
      */
      public function getControllerNamespace(): string
      {
         if (! $this->namespace) {
             return false;
         }

         return $this->getNamespace($this->getModule());
      }



      /**
       * @param string|null $module
       * @return string
      */
      public function getNamespace(string $module = null): string
      {
           if ($module) {
               $module = '\\' . trim($module, '\\');
           }

           return $this->namespace. $module;
      }



      /**
       * @param string $class
       * @return string
      */
      public function getController(string $class): string
      {
          if ($namespace = $this->getControllerNamespace()) {
            return trim($namespace, '\\') . '\\' . $class;
         }

          return $class;
      }






      /**
        * @param $action
        * @return string
      */
      public function resolveTarget($action): string
      {
          if (is_string($action)) {
              if ($this->hasActionFromString($action)) {
                  return $this->getController($action);
              }
          }

          if ($action instanceof Closure) {
              return "Closure";
          }

          if (is_array($action)) {
              return implode("::", array_values($action));
          }

          return $action;
      }




      /**
       * @param mixed $action
       * @return mixed
      */
      public function resolveAction($action)
      {
          if (is_string($action)) {
             if ($needle = $this->hasActionFromString($action)) {
                 list($controller, $method) = explode($needle, $action, 2);
                 return [$this->getController($controller), $method];
             }
          }

          return $action;
      }



      /**
       * @param string $action
       * @return false|string
      */
      protected function hasActionFromString(string $action)
      {
           foreach (['@', '::'] as $needle) {
               if (stripos($action, $needle) !== false) {
                     return $needle;
               }
           }

           return false;
      }





      /**
       * @return array
      */
      public function getRouteOptions(): array
      {
          return [
              "@prefix" => $this->getGroup()->getPrefix(),
              "@module" => $this->getGroup()->getModule(),
              "@name"   => $this->getGroup()->getName()
          ];
      }



      /**
       * @return string[]
      */
      public function getAttributeAPI(): array
      {
          return [
            'prefix' => 'api',
            'module' => 'Api',
            'name'   => 'api.'
         ];
     }

}