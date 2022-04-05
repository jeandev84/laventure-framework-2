<?php
namespace Laventure\Component\Http\Request;


use Laventure\Component\Http\Bag\CookieBag;
use Laventure\Component\Http\Bag\FileBag;
use Laventure\Component\Http\Bag\ParameterBag;
use Laventure\Component\Http\Bag\ServerBag;
use Laventure\Component\Http\Message\ServerRequestInterface;


/**
 * @ServerRequest
*/
class ServerRequest implements ServerRequestInterface
{


    /**
     * get query params from $_GET
     *
     * @var ParameterBag
    */
    public $queries;




    /**
     * get params from request $_POST
     *
     * @var array
     */
    public $request;




    /**
     * get request attributes
     *
     * @var array
     */
    public $attributes;



    /**
     * get data from $_COOKIE
     *
     * @var CookieBag
    */
    public $cookies;




    /**
     * get data from $_FILES
     *
     * @var FileBag
    */
    public $files;




    /**
     * get data from $_SERVER
     *
     * @var ServerBag
    */
    public $server;



    /**
     * @param array $queries
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     */
    public function __construct(
        array $queries = [],
        array $request = [],
        array $attributes = [],
        array $cookies = [],
        array $files = [],
        array $server = []
    )
    {
        $this->queries    =  new ParameterBag($queries);
        $this->request    =  new ParameterBag($request);
        $this->attributes =  new ParameterBag($attributes);
        $this->cookies    =  new CookieBag($cookies);
        $this->files      =  new FileBag($files);
        $this->server     =  new ServerBag($server);
    }



    /**
     * @inheritDoc
    */
    public function getServerParams(): array
    {
        return $this->server->all();
    }



    /**
     * @inheritDoc
    */
    public function getCookieParams(): array
    {
        return $this->cookies->all();
    }



    /**
     * @inheritDoc
    */
    public function withCookieParams(array $cookies)
    {
        $this->cookies->merge($cookies);

        return $this;
    }



    /**
     * @inheritDoc
    */
    public function getQueryParams(): array
    {
        return $this->queries->all();
    }



    /**
     * @inheritDoc
    */
    public function withQueryParams(array $query)
    {
        $this->queries->merge($query);

        return $this;
    }




    /**
     * @inheritDoc
    */
    public function getUploadedFiles(): array
    {
        return $this->files->all();
    }




    /**
     * @inheritDoc
    */
    public function withUploadedFiles(array $uploadedFiles)
    {
          $this->files->merge($uploadedFiles);

          return $this;
    }




    /**
     * @inheritDoc
    */
    public function getParsedBody()
    {
        return $this->request->all();
    }




    /**
     * @inheritDoc
    */
    public function withParsedBody($data)
    {
        $this->request->merge($data);

        return $this;
    }




    /**
     * @inheritDoc
    */
    public function getAttributes(): array
    {
        return $this->attributes->all();
    }




    /**
     * @inheritDoc
    */
    public function getAttribute($name, $default = null)
    {
        return $this->attributes->get($name, $default);
    }




    /**
     * @inheritDoc
    */
    public function withAttribute($name, $value)
    {
        $this->attributes->set($name, $value);

        return $this;
    }





    /**
     * @inheritDoc
    */
    public function withoutAttribute($name)
    {
        $this->attributes->remove($name);

        return $this;
    }
}