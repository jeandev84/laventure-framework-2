<?php
namespace Laventure\Component\Http\Message;


/**
 * @RequestInterface
 *
 * @see https://www.php-fig.org/psr/psr-7/
*/
interface RequestInterface extends MessageInterface
{
    /**
     * Retrieves the message's request target.
     *
     * @return string
    */
    public function getRequestTarget();



    /**
     * Return an instance with the specific request-target.
     *
     * @see http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     * @param mixed $requestTarget
     * @return static
     */
    public function withRequestTarget($requestTarget);




    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
    */
    public function getMethod();



    /**
     * Return an instance with the provided HTTP method.
     *
     * @param string $method Case-sensitive method.
     * @return static
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method);



    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri();




    /**
     * Returns an instance with the provided URI.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return static
    */
    public function withUri(UriInterface $uri, $preserveHost = false);
}