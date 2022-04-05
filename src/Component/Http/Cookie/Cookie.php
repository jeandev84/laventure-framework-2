<?php
namespace Laventure\Component\Http\Cookie;


/**
 * @Cookie
*/
class Cookie
{

    /**
     * @param string $name
     * @param $value
     * @param int $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function __construct(
        string $name,
        $value,
        int $expires = 3600,
        string $path = '/',
        string $domain = 'localhost',
        bool $secure = false,
        bool $httpOnly = false
    )
    {
        setcookie($name, $value, time() + $expires, $path, $domain, $secure, $httpOnly);
    }
}