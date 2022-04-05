<?php
namespace Laventure\Component\Routing;


/**
 * @RouterInterface
*/
interface RouterInterface
{


    /**
     * Get route domain
     *
     * @return string
    */
    public function getDomain(): string;



    /**
     * Get route collection
     *
     * @return array
    */
    public function getRoutes(): array;





    /**
     * Get current route
     *
     * @return mixed
    */
    public function getRoute();





    /**
     * Dispatch route
     *
     * @param string $requestMethod
     * @param string $requestPath
     * @return mixed
    */
    public function match(string $requestMethod, string $requestPath);






    /**
     * Generate route path
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
    */
    public function generate(string $name, array $parameters = []);
}