<?php
namespace Laventure\Component\Http\Request;


use Laventure\Component\Http\Bag\CookieBag;
use Laventure\Component\Http\Bag\FileBag;
use Laventure\Component\Http\Bag\ParameterBag;
use Laventure\Component\Http\Bag\ServerBag;


/**
 * @ServerRequestFactory
*/
class ServerRequestFactory extends ServerRequest
{

    /**
     * @param array $queries
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @return $this
    */
    public static function createFromFactory(
        array $queries = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = []
    ): self
    {
         return new static($queries, $request, $attributes, $cookies, $files, $server);
    }
}