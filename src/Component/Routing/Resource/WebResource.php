<?php
namespace Laventure\Component\Routing\Resource;

use Laventure\Component\Routing\Resource\Common\ResourceTrait;
use Laventure\Component\Routing\Resource\Contract\WebResourceInterface;
use Laventure\Component\Routing\Router;


/**
 * @WebResource
*/
class WebResource implements WebResourceInterface
{

    use ResourceTrait;


    /**
     * @inheritDoc
    */
    public function mapRoutes(Router $router)
    {
        $this->map($router, ['GET', 's', 'list', 'list'])
             ->map($router, ['GET', '/{id}', 'show', 'show'])
             ->map($router, ['GET|POST', '/create', 'create', 'create'])
             ->map($router, ['GET|POST', '/{id}/edit', 'edit', 'edit'])
             ->map($router, ['DELETE', '/delete/{id}', 'delete', 'delete']);
    }
}