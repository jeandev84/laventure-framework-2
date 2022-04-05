<?php
namespace Laventure\Component\Routing\Generator;

use Laventure\Component\Routing\RouterInterface;


/**
 * @UrlGenerator
 */
class UrlGenerator implements UrlGeneratorInterface
{


    /**
     * @var RouterInterface
    */
    protected $router;




    /**
     * @var string
    */
    protected $networkDomain;




    /**
     * @param RouterInterface $router
     * @param string $networkDomain
    */
    public function __construct(RouterInterface $router, string $networkDomain)
    {
        $this->router        = $router;
        $this->networkDomain = $networkDomain;
    }




    /**
     * @inheritDoc
    */
    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_URL)
    {
        if ($path = $this->router->generate($name, $parameters)) {
            return $this->generatePath($path, [], $referenceType);
        }

        return $this->generatePath($name, $parameters, $referenceType);
    }




    /**
     * @param string $path
     * @param array $parameters
     * @param int $referenceType
     * @return void
    */
    public function generatePath(string $path, array $parameters = [], int $referenceType = self::ABSOLUTE_URL): string
    {
         $path = $this->removeSlashes($path);

         $qs = $parameters ? '?'. $this->buildQueryParams($parameters) : '';

         switch ($referenceType) {

            // http://mysqite.com/admin/users?page=1&order=asc&sort=name
            case self::ABSOLUTE_URL:
                return $this->networkDomain . '/'. $path . $qs;

            // http://mysqite.com/admin/users
            case self::ABSOLUTE_PATH;
                return $this->networkDomain . '/'. $path;

            // /users
            case self::RELATIVE_PATH:
                return $path;

            // http://mysqite.com/
            case self::NETWORK_PATH:
                return $this->networkDomain;
        }

        return $this->networkDomain;
    }




    /**
     * @param array $parameters
     * @return string
    */
    public function buildQueryParams(array $parameters): string
    {
        return http_build_query($parameters);
    }



    /**
     * @param string $path
     * @return string
    */
    protected function removeSlashes(string $path): string
    {
        return trim($path, '\\/');
    }
}