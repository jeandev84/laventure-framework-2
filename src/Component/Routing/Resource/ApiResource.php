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
           return [
               'list' => [
                   'methods'  => 'GET',
                   'path'     => $this->makeRoutePath('s'),
                   'action'   => $this->makeRouteAction('list'),
                   'name'     => $this->makeRouteName('list')
               ],
               'show' => [
                   'methods'  => 'GET',
                   'path'     => $this->makeRoutePath('/{id}'),
                   'action'   => $this->makeRouteAction('show'),
                   'name'     => $this->makeRouteName('show')
               ],
               'create' => [
                   'methods'  => 'POST',
                   'path'     => $this->makeRoutePath('/create'),
                   'action'   => $this->makeRouteAction('create'),
                   'name'     => $this->makeRouteName('create')
               ],
               'edit' => [
                   'methods'  => 'PUT',
                   'path'     => $this->makeRoutePath('/{id}/edit'),
                   'action'   => $this->makeRouteAction('edit'),
                   'name'     => $this->makeRouteName('edit')
               ],
               'destroy' => [
                   'methods'  => 'DELETE',
                   'path'     => $this->makeRoutePath('/destroy/{id}'),
                   'action'   => $this->makeRouteAction('destroy'),
                   'name'     => $this->makeRouteName('destroy')
               ]
           ];
       }
}