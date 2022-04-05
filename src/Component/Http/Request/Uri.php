<?php
namespace Laventure\Component\Http\Request;


use Laventure\Component\Http\Bag\ServerBag;
use Laventure\Component\Http\Message\UriInterface;


/**
 * @Uri
*/
class Uri implements UriInterface
{


    /**
     * Get scheme
     *
     * @var string
    */
    protected $scheme;




    /**
     * Get host
     *
     * @var string
    */
    protected $host;





    /**
     * Get username
     *
     * @var string
    */
    protected $username;




    /**
     * Get password
     *
     * @var string
    */
    protected $password;







    /**
     * Get port
     *
     * @var string
    */
    protected $port;





    /**
     * Get path
     *
     * @var string
    */
    protected $path;





    /**
     * Query string
     *
     * @var string
    */
    protected $queryString;





    /**
     * Fragment request
     *
     * @var string
    */
    protected $fragment;



    /**
     * @param ServerBag|null $server
    */
    public function __construct(ServerBag $server = null)
    {
          if ($server) {
               $this->setDefaults($server);
          }
    }





    /**
     * @inheritDoc
    */
    public function getScheme()
    {
        return $this->scheme;
    }



    /**
     * @inheritDoc
    */
    public function getAuthority()
    {
        return $this->password;
    }



    /**
     * @inheritDoc
    */
    public function getUserInfo()
    {
        return $this->username;
    }





    /**
     * @inheritDoc
    */
    public function getHost()
    {
        return $this->host;
    }




    /**
     * @inheritDoc
    */
    public function getPort()
    {
        return $this->port;
    }




    /**
     * @inheritDoc
     */
    public function getPath()
    {
        return $this->path;
    }



    /**
     * @inheritDoc
    */
    public function getQuery()
    {
        return $this->queryString;
    }



    /**
     * @inheritDoc
    */
    public function getFragment()
    {
        return $this->fragment;
    }




    /**
     * @inheritDoc
    */
    public function withScheme($scheme)
    {
         $this->scheme = $scheme;

         return $this;
    }



    /**
     * @inheritDoc
    */
    public function withUserInfo($user, $password = null)
    {
         $this->username = $user;
         $this->password = $password;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function withHost($host)
    {
        $this->host = $host;

        return $this;
    }




    /**
     * @inheritDoc
    */
    public function withPort($port)
    {
         $this->port = $port;

         return $this;
    }




    /**
     * @inheritDoc
    */
    public function withPath($path)
    {
         $this->path = $path;

         return $this;
    }





    /**
     * @inheritDoc
    */
    public function withQuery($query)
    {
         $this->queryString = $query;

         return $this;
    }





    /**
     * @inheritDoc
     */
    public function withFragment($fragment)
    {
        $this->fragment = $fragment;

        return $this;
    }



    /**
     * @inheritDoc
    */
    public function __toString()
    {
         return sprintf('%s%s', $this->path, $this->queryString);
    }



    /**
     * @param ServerBag $server
     * @return void
    */
    protected function setDefaults(ServerBag $server)
    {
        $this->withScheme($server->getScheme())
             ->withUserInfo($server->getUsername(), $server->getPassword())
             ->withHost($server->getHost())
             ->withPort($server->getPort())
             ->withPath($server->getPathInfo())
             ->withQuery($server->getQueryString())
             ->withFragment(null);
    }
}