<?php
namespace Laventure\Component\Routing\Generator;

/**
 * @UrlGeneratorInterface
 */
interface UrlGeneratorInterface
{

    /**
     * Generate an absolute url, e.g. "http://site.com/post/2"
    */
    public const ABSOLUTE_URL = 0;



    /**
     * Generate an absolute path, e.g. "/post/2"
    */
    public  const ABSOLUTE_PATH = 1;



    /**
     * Generate a relative path based on the current request path, e.g. "../parent/path"
    */
    public const RELATIVE_PATH = 2;




    /**
     * Generate a network path, e.g "//site.com/path/to/post/2"
     * get path without scheme
    */
    public const NETWORK_PATH = 3;




    /**
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     * @return mixed
    */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_URL);
}