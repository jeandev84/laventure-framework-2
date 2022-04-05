<?php
namespace Laventure\Component\Http\Request;


use Laventure\Component\Http\Bag\InputBag;
use Laventure\Component\Http\Bag\RequestHeaderBag;
use Laventure\Component\Http\Message\RequestInterface;
use Laventure\Component\Http\Message\StreamInterface;
use Laventure\Component\Http\Message\UriInterface;
use Laventure\Component\Http\Session\Session;


/**
 * @Request
 *
 * @package Laventure\Component\Http\Request
*/
class Request extends ServerRequestFactory implements RequestInterface
{

        /**
         * @var RequestHeaderBag
        */
        protected $headers;



        /**
         * @var string
        */
        protected $version;




        /**
         * @var string
        */
        protected $method;




        /**
          * @var string
        */
        protected $requestTarget;




        /**
         * @var Uri
        */
        public $uri;




        /**
         * @var string
        */
        public $content;




        /**
         * @var Session
        */
        protected $sessions;




        /**
         * @param array $queries
         * @param array $request
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param string|null $content
        */
       public function __construct(
           array $queries = [],
           array $request = [],
           array $attributes = [],
           array $cookies = [],
           array $files = [],
           array $server = [],
           string $content = null
       )
       {
           parent::__construct($queries, $request, $attributes, $cookies, $files, $server);

           $this->uri           = new Uri($this->server);
           $this->sessions      = new Session();
           $this->headers       = new RequestHeaderBag();
           $this->content       = $content;
       }





        /**
         * @return $this
        */
        public static function createFromGlobals(): self
        {
             $request = static::createFromFactory($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER, 'php://input');

             if($request->hasContentFormUrlEncoded() && $request->hasResourceMethods()) {
                  parse_str($request->getContent(), $data);
                  $request->request = new InputBag($data);
             }

             return $request;
        }




        /**
         * @param array $queries
         * @param array $request
         * @param array $attributes
         * @param array $cookies
         * @param array $files
         * @param array $server
         * @param string|null $content
         * @return Request
        */
        public static function createFromFactory(
            array $queries = [],
            array $request = [],
            array $attributes = [],
            array $cookies = [],
            array $files = [],
            array $server = [],
            string $content = null
        ): ServerRequestFactory
        {
            return new static($queries, $request, $attributes, $cookies, $files, $server, $content);
        }




       /**
        * @param array $attributes
        * @return void
       */
       public function setAttributes(array $attributes)
       {
            $this->attributes->merge($attributes);
       }





       /**
         * @inheritDoc
       */
       public function getProtocolVersion(): string
       {
           if ($this->version) {
               return $this->version;
           }

           return $this->version = $this->server->getProtocol();
       }




        /**
         * @inheritDoc
        */
        public function withProtocolVersion($version)
        {
            $this->version = $version;

            return $this;
        }



         /**
          * @inheritDoc
         */
         public function getHeaders(): array
         {
             return $this->headers->all();
         }



        /**
         * @inheritDoc
        */
        public function hasHeader($name): bool
        {
            return $this->headers->has($name);
        }




        /**
         * @inheritDoc
        */
        public function getHeader($name)
        {
             return $this->headers->get($name);
        }



        /**
         * @inheritDoc
        */
        public function getHeaderLine($name)
        {
               // todo implements
        }




        /**
         * @inheritDoc
         */
        public function withHeader($name, $value): self
        {
              $this->headers->set($name, $value);

              return $this;
        }




        /**
         * @inheritDoc
        */
        public function withAddedHeader($name, $value)
        {
             // todo implements
        }



        /**
         * @inheritDoc
        */
        public function withoutHeader($name)
        {
             // todo implements
        }




        /**
         * @inheritDoc
        */
        public function getBody()
        {
            // todo implements
        }




        /**
         * @inheritDoc
        */
        public function withBody(StreamInterface $body)
        {
             // todo implement
        }



        /**
         * @inheritDoc
        */
        public function getRequestTarget(): string
        {
             return $this->requestTarget;
        }




        /**
         * @param mixed $path
         * @return string
        */
        public function decodeURL($path): string
        {
             return urldecode($path);
        }




        /**
         * @param mixed $path
         * @return string
        */
        public function encodeURL($path): string
        {
             return urlencode($path);
        }



        /**
         * @param $requestTarget
         * @return $this
        */
        public function withRequestTarget($requestTarget): Request
        {
              $this->requestTarget = $this->decodeURL($requestTarget);

              return $this;
        }



        /**
          * @return string
        */
        public function baseURL(): string
        {
            return $this->decodeURL($this->server->getBaseURL());
        }




        /**
          * @return string
        */
        public function url(): string
        {
            return $this->decodeURL($this->server->getURL());
        }




        /**
         * @inheritDoc
        */
        public function getMethod(): string
        {
            return $this->method ?? $this->server->getMethodOrDefault();
        }




        /**
         * @inheritDoc
        */
        public function withMethod($method)
        {
            $this->method = $method;

            $this->server->setMethod($method);

            return $this;
        }




        /**
         * @inheritDoc
        */
        public function getUri(): UriInterface
        {
             return $this->uri;
        }



        /**
         * @inheritDoc
        */
        public function withUri(UriInterface $uri, $preserveHost = false)
        {
             $this->uri = $uri;

             return $this;
        }




        /**
          * @return string
        */
        public function getRequestUri(): string
        {
            return $this->decodeURL($this->server->getRequestUri());
        }





        /**
         * Determine if the protocol is secure
         *
         * @return bool
        */
        public function isSecure(): bool
        {
            return $this->server->isSecure();
        }




        /**
         * @param string $type
         * @return bool
        */
        public function isMethod(string $type): bool
        {
            return $this->getMethod() === strtoupper($type);
        }




        /**
         * @return bool
        */
        public function isGET(): bool
        {
            return $this->isMethod('GET');
        }




        /**
         * @return bool
        */
        public function isPOST(): bool
        {
            return $this->isMethod('POST');
        }




        /**
         * @return bool
        */
        public function isPUT(): bool
        {
            return $this->isMethod('POST');
        }




        /**
         * @return bool
        */
        public function isDELETE(): bool
        {
            return $this->isMethod('DELETE');
        }




        /**
         * @return string
        */
        public function getScheme(): string
        {
            return $this->server->getScheme();
        }



        /**
         * @return bool
        */
        public function isXhr(): bool
        {
            return $this->server->isXhr();
        }



        /**
         * @param string $host
         * @return bool
        */
        public function isValidHost(string $host): bool
        {
            return $this->server->getHost() === $host;
        }




        /**
         * @return string|null
        */
        public function getContent(): ?string
        {
            if ($this->content) {
                return $this->content;
            }

            return file_get_contents($this->content);
        }




        /**
         * @return string
        */
        public function getContentType(): string
        {
            return $this->headers->getContentType();
        }



        /**
         * @return bool
        */
        protected function hasContentFormUrlEncoded(): bool
        {
              return $this->headers->hasContentFormUrlEncoded();
        }




        /**
         * @param array $methods
         * @return bool
        */
        protected function methodIn(array $methods): bool
        {
            return \in_array($this->toUpperMethod(), $methods);
        }





        /**
         * @return bool
        */
        protected function hasResourceMethods(): bool
        {
            return $this->methodIn(['PUT', 'DELETE', 'PATCH']);
        }




        /**
         * @return string
        */
        protected function toUpperMethod(): string
        {
            return strtoupper($this->getMethod());
        }

}