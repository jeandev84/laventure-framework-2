<?php
namespace Laventure\Component\Routing\Resource;

use Laventure\Component\Routing\Resource\Common\AbstractResource;
use Laventure\Component\Routing\Resource\Contract\WebResourceInterface;


/**
 * @WebResource
*/
class WebResource extends AbstractResource implements WebResourceInterface
{


    /**
     * @return \string[][]
    */
    public function getParams(): array
    {
          return [
              ['GET', 's', 'list', 'list'],
              ['GET', '/{id}', 'show', 'show'],
              ['GET|POST', '/create', 'create', 'create'],
              ['GET|POST', '/{id}/edit', 'edit', 'edit'],
              ['DELETE', '/delete/{id}', 'delete', 'delete']
          ];
    }
}