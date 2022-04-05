<?php
namespace Laventure\Component\Routing\Resource;


use Laventure\Component\Routing\Resource\Common\ResourceTrait;
use Laventure\Component\Routing\Resource\Contract\ApiResourceInterface;
use Laventure\Component\Routing\Router;


/**
 * @ApiResource
*/
class ApiResource implements ApiResourceInterface
{

        use ResourceTrait;


        /**
         * @inheritDoc
        */
        public function mapRoutes(Router $router)
        {
            $this->map($router, ['GET', 's', 'list', 'list'])
                 ->map($router, ['GET', '/{id}', 'show', 'show'])
                 ->map($router, ['POST', '/create', 'create', 'create'])
                 ->map($router, ['PUT', '/{id}/edit', 'edit', 'edit'])
                 ->map($router, ['DELETE', '/{id}/delete', 'delete', 'delete'])
            ;
        }
}