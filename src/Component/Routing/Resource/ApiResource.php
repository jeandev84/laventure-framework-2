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
                   'path'     => $this->generatePath('s'),
                   'action'   => $this->generateAction('list'),
                   'name'     => $this->generateName('list')
               ],
               'show' => [
                   'methods'  => 'GET',
                   'path'     => $this->generatePath('/{id}'),
                   'action'   => $this->generateAction('show'),
                   'name'     => $this->generateName('show')
               ],
               'create' => [
                   'methods'  => 'POST',
                   'path'     => $this->generatePath('/create'),
                   'action'   => $this->generateAction('create'),
                   'name'     => $this->generateName('create')
               ],
               'edit' => [
                   'methods'  => 'PUT',
                   'path'     => $this->generatePath('/{id}/edit'),
                   'action'   => $this->generateAction('edit'),
                   'name'     => $this->generateName('edit')
               ],
               'destroy' => [
                   'methods'  => 'DELETE',
                   'path'     => $this->generatePath('/destroy/{id}'),
                   'action'   => $this->generateAction('destroy'),
                   'name'     => $this->generateName('destroy')
               ]
           ];
       }
}