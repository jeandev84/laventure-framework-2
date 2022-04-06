<?php
namespace Laventure\Component\Routing\Resource;


use Laventure\Component\Routing\Resource\Common\AbstractResource;
use Laventure\Component\Routing\Resource\Contract\ApiResourceInterface;


/**
 * @ApiResource
*/
class ApiResource extends AbstractResource implements ApiResourceInterface
{

       /**
        * @return \string[][]
       */
       public function getParams(): array
       {
            /*
            return [
              ['GET', 's', 'list', 'list'],
              ['GET', '/{id}', 'show', 'show'],
              ['POST', '/create', 'create', 'create'],
              ['PUT', '/{id}/edit', 'edit', 'edit'],
              ['DELETE', '/{id}/destroy', 'destroy', 'destroy']
           ];
           */

           return [];
       }
}