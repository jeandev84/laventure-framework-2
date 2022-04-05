<?php
namespace Laventure\Component\Routing\Collection;


use Laventure\Component\Routing\Collection\Exception\BadControllerException;
use Laventure\Component\Routing\Collection\Exception\BadMethodException;


/**
 * @Route
*/
class Route implements \ArrayAccess
{


        /**
          * route domain
          *
          * @var string
        */
        protected $domain;





        /**
         * route path
         *
         * @var string
        */
        protected $path;





        /**
         * route name
         *
         * @var string
        */
        protected $name;





        /**
         * route methods
         *
         * @var array
        */
        protected $methods;




        /**
         * route callback
         *
         * @var callable
        */
        protected $callback;





        /**
         * Route action
         *
         * @var array
        */
        protected $action = [
            "controller" => "",
            "action"     => ""
        ];




        /**
         * route options
         *
         * @var array
        */
        protected $options = [];




        /**
         * route patterns
         *
         * @var array
        */
        protected $patterns = [];





        /**
         * route matches params
         *
         * @var array
        */
        protected $matches = [];





        /**
         * route middlewares
         *
         * @var array
        */
        protected $middlewares = [];





        /**
         * Route constructor
         *
         * @param array $methods
         * @param string $path
         * @param mixed $action
         * @param array $options
        */
        public function __construct(array $methods = [], string $path = '', $action = null, array $options = [])
        {
              $this->methods($methods);
              $this->path($path);
              $this->action($action);
              $this->options($options);
        }





        /**
         * get route domain
         *
         * @return string
        */
        public function getDomain(): string
        {
            return $this->domain;
        }




        /**
         * get route methods
         *
         * @return array
        */
        public function getMethods(): array
        {
            return $this->methods;
        }




        /**
         * @param string $separator
         * @return string
        */
        public function getMethodsToString(string $separator = '|'): string
        {
             return implode($separator, $this->methods);
        }




        /**
         * get route path
         *
         * @return string
        */
        public function getPath(): string
        {
            return $this->path ?? '/';
        }




        /**
          * get route name
          *
          * @return string
        */
        public function getName()
        {
            return $this->name;
        }





        /**
         * get route patterns
         *
         * @return array
        */
        public function getPatterns(): array
        {
            return $this->patterns;
        }




        /**
         * get matches params
         *
         * @return array
        */
        public function getMatches(): array
        {
            return $this->matches;
        }




        /**
         * get options
         *
         * @return array
        */
        public function getOptions(): array
        {
            return $this->options;
        }




        /**
         * get option
         *
         * @param $key
         * @param null $default
         * @return mixed|null
        */
        public function getOption($key, $default = null)
        {
            return $this->options[$key] ?? $default;
        }




        /**
         * get middlewares
         *
         * @return array
        */
        public function getMiddlewares(): array
        {
            return $this->middlewares;
        }





        /**
         * @return mixed|null
        */
        public function getGroupName()
        {
            return $this->getOption('@name');
        }




        /**
          * @return mixed|null
        */
        public function getTarget()
        {
            return $this->getOption("@target");
        }




        /**
         * Set route methods
         *
         * @param array $methods
         * @return $this
        */
        public function methods(array $methods): self
        {
            $this->methods = $methods;

            return $this;
        }




        /**
         * Set route path
         *
         * @param string $path
         * @return $this
        */
        public function path(string $path): self
        {
            $this->path = $path;

            return $this;
        }






        /**
         * @param $action
         * @return $this
        */
        public function action($action): self
        {
            if (is_array($action)) {
                $actions = array_values($action);
                if (count($actions) === 2) {
                    list($controller, $method) = $actions;
                    $this->controller($controller, $method);
                }
            }

            if (is_callable($action)) {
                $this->callback($action);
            }

            return $this;
        }



        /**
          * @param $target
          * @return void
        */
        public function target($target)
        {
            $this->option("@target", $target);
        }





        /**
         * @param $controller
         * @param string $action
         * @return $this
        */
        public function controller($controller, string $action): self
        {
            $this->action["controller"] = $controller;
            $this->action["action"]     = $action;

            return $this;
        }




        /**
         * set route domain
         *
         * @param string $domain
         * @return $this
        */
        public function domain(string $domain): self
        {
            $this->domain = $domain;

            return $this;
        }




        /**
         * @param callable $callback
         * @return void
        */
        public function callback(callable $callback) {
            $this->callback = $callback;
        }




        /**
         * @return callable
        */
        public function getCallback(): callable
        {
            return $this->callback;
        }




        /**
         * set route name
         *
         * @param string $name
         * @return $this
        */
        public function name(string $name): self
        {
            $this->name = $this->getGroupName() . $name;

            return $this;
        }




        /**
         * set route middlewares
         *
         * @param $middlewares
         * @return $this
        */
        public function middlewares($middlewares): self
        {
            $this->middlewares = array_merge($this->middlewares, (array) $middlewares);

            return $this;
        }





        /**
         * @param string $name
         * @return $this
        */
        public function groupName(string $name): Route
        {
            $this->option('@name', $name);

            return $this;
        }




        /**
         * @param array $options
         * @return $this
        */
        public function options(array $options): self
        {
             $this->options = array_merge($this->options, $options);

             return $this;
        }




        /**
         * @param string $key
         * @param $value
         * @return $this
        */
        public function option(string $key, $value): self
        {
             $this->options[$key] = $value;

             return $this;
        }





        /**
         * set route regex params
         *
         * @param $name
         * @param null $regex
         * @return $this
        */
        public function where($name, $regex = null): self
        {
            foreach ($this->parseWhere($name, $regex) as $name => $regex) {
                $this->patterns[$name] =  $this->generatePattern($name, $regex);
            }

            return $this;
        }




        /**
         * @param string $name
         * @return $this
         */
        public function whereNumber(string $name): self
        {
            return $this->where($name, '\d+');
        }






        /**
         * @param string $name
         * @return $this
        */
        public function whereText(string $name): self
        {
            return $this->where($name, '\w+');
        }




        /**
         * @param string $name
         * @return $this
        */
        public function whereAlphaNumeric(string $name): self
        {
            return $this->where($name, '[^a-z_\-0-9]'); // [^a-z_\-0-9]
        }





        /**
         * @param string $name
         * @return $this
        */
        public function whereSlug(string $name): self
        {
            return $this->where($name, '[a-z\-0-9]+');
        }




        /**
         * @param string $name
         * @return $this
        */
        public function anything(string $name): self
        {
            return $this->where($name, '.*');
        }




        /**
         * match methods
         *
         * @param string $requestMethod
         * @return bool
        */
        public function matchMethod(string $requestMethod): bool
        {
             if (\in_array($requestMethod, $this->methods)) {

                 $this->options(compact('requestMethod'));

                 return true;
             }

             return false;
        }




        /**
         * Determine if request uri match route pattern
         *
         * @param string $requestPath
         * @return bool
        */
        public function matchURI(string $requestPath): bool
        {
            if (preg_match($pattern = $this->getPattern(), $this->resolveURL($requestPath), $matches)) {

                $this->matches = $this->filteredMatches($matches);

                $this->options(compact('pattern', 'requestPath'));

                return true;
            }

            return false;
        }




        /**
         * get route pattern
         *
         * @return string
        */
        public function getPattern(): string
        {
            $path = $this->getPath();

            if ($this->patterns) {
                $path = $this->convertedPath($path, $this->patterns);
            }

            return '#^' . $this->loadPath($path) . '$#i';
        }



        /**
         * @param string $requestMethod
         * @param string $requestPath
         * @return bool
        */
        public function match(string $requestMethod, string $requestPath): bool
        {
             return $this->matchMethod($requestMethod) && $this->matchURI($requestPath);
        }




        /**
         * @return bool
        */
        public function callable(): bool
        {
            return is_callable($this->callback);
        }





        /**
         * @return false|mixed
        */
        public function call()
        {
            if (! $this->callable()) {
                return false;
            }

            return call_user_func_array($this->callback, $this->getMatchValues());
        }




        /**
         * @return array
        */
        public function getMatchValues(): array
        {
             return array_values($this->getMatches());
        }





        /**
         * Convert path parameters
         *
         * @param array $params
         * @return string
        */
        public function generatePath(array $params): string
        {
             $path = $this->getResolvedPath();

             foreach ($params as $k => $v) {
                $path = preg_replace(["#{{$k}}#", "#{{$k}.?}#"], [$v, $v], $path);
             }

             return sprintf('/%s', $path);
        }




        /**
         * resolve request path
         *
         * @param $path
         * @return string
        */
        protected function resolveURL($path): string
        {
             $path = parse_url($path, PHP_URL_PATH);

             return $this->loadPath($path);
        }



        /**
         * Get controller name
         *
         * @return string|null
        */
        public function getControllerName(): ?string
        {
             return $this->action["controller"] ?? null;
        }



        /**
         * Get Action name
         *
         * @return string
        */
        public function getActionName(): string
        {
             return $this->action["action"] ?? "";
        }




        /**
         * Get controller class
         *
         * @return string|null
        */
        public function getController(): ?string
        {
              return (function () {

                  if (! class_exists($controller = $this->getControllerName())) {
                     throw new BadControllerException("Controller '{$controller}' does not exist.");
                  }

                  return $controller;

              })();
        }




        /**
         * Get callable action inside controller.
         *
          * @return string
        */
        public function getAction(): string
        {
             return (function () {

                 if (! method_exists($controller = $this->getController(), $action = $this->getActionName())) {
                     throw new BadMethodException("Method '{$action}' does not exist in controller {$controller}");
                 }

                 return $action;

             })();
        }





        /**
         * Resolve path
         *
         * @return string
        */
        protected function getResolvedPath(): string
        {
             return $this->removeSlashes($this->path);
        }






        /**
         * prepare path
         *
         * @param $path
         * @return string
        */
        protected function loadPath($path): string
        {
            return sprintf('/%s', $this->removeSlashes($path));
        }




        /**
         * remove trailing slashes
         *
         * @param $path
         * @return string
        */
        protected function removeSlashes($path): string
        {
             return trim($path, '\\/');
        }





        /**
         * convert patterns
         *
         * @param string $path
         * @param array $patterns
         * @return string
        */
        protected function convertedPath(string $path, array $patterns): string
        {
            foreach ($patterns as $k => $v) {
                $path = preg_replace(["#{{$k}}#", "#{{$k}.?}#"], [$v, $v = '?'. $v .'?'], $path);
            }

            return $path;
        }



        /**
         * filtered matches
         *
         * @param array $matches
         * @return array
        */
        protected function filteredMatches(array $matches): array
        {
             return array_filter($matches, function ($key) {

                 return ! is_numeric($key);

             }, ARRAY_FILTER_USE_KEY);
        }



        /**
         * Determine parses
         *
         * @param $name
         * @param $regex
         * @return array
        */
        protected function parseWhere($name, $regex): array
        {
             return \is_array($name) ? $name : [$name => $regex];
        }



        /**
         * @param $name
         * @param $regex
         * @return string
        */
        protected function generatePattern($name, $regex): string
        {
            $regex = str_replace('(', '(?:', $regex);

            return sprintf('(?P<%s>%s)', $name, $regex);
        }



        /**
         * @param mixed $offset
         * @return bool
        */
        public function offsetExists($offset): bool
        {
            return property_exists($this, $offset);
        }



        /**
         * @param mixed $offset
         * @return mixed|void
        */
        public function offsetGet($offset)
        {
            if(property_exists($this, $offset)) {
                return $this->{$offset};
            }

            return null;
        }




        /**
         * @param mixed $offset
         * @param mixed $value
        */
        public function offsetSet($offset, $value)
        {
            if(property_exists($this, $offset)) {
                $this->{$offset} = $value;
            }
        }



        /**
         * @param mixed $offset
        */
        public function offsetUnset($offset)
        {
            if(property_exists($this, $offset)) {
                unset($this->{$offset});
            }
        }

}