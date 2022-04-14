<?php
namespace Laventure\Component\Http\Response;


use Laventure\Component\Http\Bag\ResponseHeaderBag;
use Laventure\Component\Http\Client\Message;
use Laventure\Component\Http\Message\ResponseInterface;
use Laventure\Component\Http\Message\StatusCodeInterface;
use Laventure\Component\Http\Message\StreamInterface;
use Laventure\Component\Http\Response\Common\StatusCode;



/**
 * @Response
*/
class Response extends Message implements ResponseInterface, StatusCodeInterface
{


       use StatusCode;


      /**
       * @var mixed
      */
      protected $content;




      /**
       * @var int
      */
      protected $statusCode;




      /**
        * @var string
      */
      protected $reasonPhrase = '';




      /**
       * @var ResponseHeaderBag
      */
      public $headers;




      /**
       * @var string
      */
      protected $version = 'HTTP/1.0';




      /**
       * @param string|null $content
       * @param int $statusCode
       * @param array $headers
      */
      public function __construct($content = null, int $statusCode = 200, array $headers = [])
      {
           if($content) {
               $this->setContent($content);
           }

           $this->statusCode = $statusCode;
           $this->headers    = new ResponseHeaderBag($headers);
      }




      /**
       * @return string
      */
      public function getContent(): string
      {
           return (string) $this->content;
      }



      /**
       * @param string $content
       * @return void
      */
      public function setContent($content)
      {
           $this->content = $content;
      }



      /**
       * @return int
      */
      public function getStatusCode(): int
      {
           return $this->statusCode;
      }




      /**
        * @param int $statusCode
        * @return void
      */
      public function setStatusCode(int $statusCode)
      {
           $this->statusCode = $statusCode;
      }



      /**
       * @return string
      */
      public function getReasonPhrase(): string
      {
          return $this->reasonPhrase;
      }




    /**
     * @return array
    */
    public function getHeaders(): array
    {
        return $this->headers->all();
    }




    /**
     * @param $code
     * @param $reasonPhrase
     * @return $this|Response
    */
    public function withStatus($code, $reasonPhrase = null): self
    {
         $this->statusCode   = $code;
         $this->reasonPhrase = $reasonPhrase;

         return $this;
    }



    /**
     * @param $name
     * @param $value
     * @return $this
    */
    public function withHeader($name, $value = null): self
    {
      $this->headers->parse($name, $value);

      return $this;
    }




    /**
     * @param $name
     * @return Response
    */
    public function withoutHeader($name): self
    {
       $this->headers->remove($name);

       return $this;
    }




    /**
     * @param $name
     * @param $value
     * @return self
    */
    public function withAddedHeader($name, $value): self
    {
       $this->headers->merge([$name => $value]);

        return $this;
    }



    /**
     * @param string $version
     * @return void
    */
    public function setProtocolVersion(string $version)
    {
          $this->version = $version;
    }




    /**
     * @return self
    */
    public function sendHeaders(): Response
    {
        $this->headers->sendHeaders($this->statusCode);

        return $this;
    }




    /**
     * @return void
    */
    public function sendBody()
    {
        echo $this->content;
    }



    /**
     * @return Response
    */
    public function send(): Response
    {
          if (headers_sent()) {
              return $this;
          }

          return $this->sendHeaders();
    }




    /**
    * @return string
    */
    public function __toString()
    {
      return (string) $this->getContent();
    }
}