<?php
namespace Laventure\Component\Http\Client;


use Laventure\Component\Http\Message\MessageInterface;
use Laventure\Component\Http\Message\StreamInterface;


/**
 * @Message
*/
class Message implements MessageInterface
{


    /**
     * @var string
    */
    protected $version;




    /**
     * @var mixed
    */
    protected $headers;




    /**
     * @var StreamInterface
    */
    protected $body;





    /**
     * @return string
    */
    public function getProtocolVersion()
    {
        return $this->version;
    }



    /**
     * @param $version
     * @return self
    */
    public function withProtocolVersion($version)
    {
        $this->version = $version;

        return $this;
    }




    /**
     * @return mixed
    */
    public function getHeaders()
    {
        return $this->headers;
    }



    /**
     * @param $name
     * @return bool|void
    */
    public function hasHeader($name)
    {
         return array_key_exists($name, $this->headers);
    }



    /**
     * @param $name
     * @return string[]|void
    */
    public function getHeader($name)
    {
         if (! $this->hasHeader($name)) {
             return null;
         }

         return $this->headers[$name];
    }



    /**
     * @param $name
     * @return string|void
    */
    public function getHeaderLine($name)
    {
         //todo implements
    }



    /**
     * @param $name
     * @param $value
     * @return self
    */
    public function withHeader($name, $value)
    {
         $this->headers[$name] = $value;

         return $this;
    }



    /**
     * @param $name
     * @param $value
     * @return self
    */
    public function withAddedHeader($name, $value)
    {
         $this->headers = array_merge($this->headers, [$name => $value]);

         return $this;
    }




    /**
     * @param $name
     * @return self
    */
    public function withoutHeader($name)
    {
         unset($this->headers[$name]);

         return $this;
    }


    /**
     * @return StreamInterface|void
    */
    public function getBody()
    {
        return $this->body;
    }




    /**
     * @param StreamInterface $body
     * @return self
    */
    public function withBody(StreamInterface $body)
    {
        $this->body = $body;

        return $this;
    }
}