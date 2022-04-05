<?php
namespace Laventure\Component\Http\Message;

/**
 * Value object representing a URI.
 *
 * @UriInterface
 * @see https://www.php-fig.org/psr/psr-7/
 *
 * @see http://tools.ietf.org/html/rfc3986 (the URI specification)
 */
interface UriInterface
{

    /**
     * Retrieve the scheme component of the URI.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.1
     * @return string The URI scheme.
    */
    public function getScheme();




    /**
     * Retrieve the authority component of the URI.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-3.2
     * @return string The URI authority, in "[user-info@]host[:port]" format.
     */
    public function getAuthority();




    /**
     * Retrieve the user information component of the URI..
     *
     * @return string The URI user information, in "username[:password]" format.
     */
    public function getUserInfo();




    /**
     * Retrieve the host component of the URI.
     *
     * @see http://tools.ietf.org/html/rfc3986#section-3.2.2
     * @return string The URI host.
     */
    public function getHost();




    /**
     * Retrieve the port component of the URI.
     *
     * @return null|int The URI port.
    */
    public function getPort();




    /**
     * Retrieve the path component of the URI.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.3
     * @return string The URI path.
     */
    public function getPath();




    /**
     * Retrieve the query string of the URI.
     *
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.4
     * @return string The URI query string.
    */
    public function getQuery();




    /**
     * Retrieve the fragment component of the URI.
     *
     * @see https://tools.ietf.org/html/rfc3986#section-2
     * @see https://tools.ietf.org/html/rfc3986#section-3.5
     * @return string The URI fragment.
    */
    public function getFragment();




    /**
     * Return an instance with the specified scheme.
     *
     * @param string $scheme The scheme to use with the new instance.
     * @return static A new instance with the specified scheme.
     * @throws \InvalidArgumentException for invalid schemes.
     * @throws \InvalidArgumentException for unsupported schemes.
     */
    public function withScheme($scheme);



    /**
     * Return an instance with the specified user information.
     *
     * @param string $user The user name to use for authority.
     * @param null|string $password The password associated with $user.
     * @return static A new instance with the specified user information.
    */
    public function withUserInfo($user, $password = null);




    /**
     * Return an instance with the specified host.
     *
     * @param string $host The hostname to use with the new instance.
     * @return static A new instance with the specified host.
     * @throws \InvalidArgumentException for invalid hostnames.
    */
    public function withHost($host);




    /**
     * Return an instance with the specified port.
     *
     * @return static A new instance with the specified port.
     * @throws \InvalidArgumentException for invalid ports.
    */
    public function withPort($port);




    /**
     * Return an instance with the specified path.
     *
     * @param string $path The path to use with the new instance.
     * @return static A new instance with the specified path.
     * @throws \InvalidArgumentException for invalid paths.
    */
    public function withPath($path);




    /**
     * Return an instance with the specified query string.
     *
     * @param string $query The query string to use with the new instance.
     * @return static A new instance with the specified query string.
     * @throws \InvalidArgumentException for invalid query strings.
     */
     public function withQuery($query);




     /**
      * Return an instance with the specified URI fragment.
      *
      * @param string $fragment The fragment to use with the new instance.
      * @return static A new instance with the specified fragment.
     */
     public function withFragment($fragment);




    /**
     * Return the string representation as a URI reference.
     *
     * @return string
    */
    public function __toString();
}