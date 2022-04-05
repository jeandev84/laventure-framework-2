<?php
namespace Laventure\Component\Database\Connection;


use Laventure\Component\Database\Connection\Contract\ResultCollectionInterface;




/**
 * @ResultCollection
*/
class ResultCollection implements ResultCollectionInterface
{


    /**
     * @var array
    */
    protected $objects = [];




    /**
     * @param $result
     * @return void
    */
    public function collect($result)
    {
        if (is_object($result)) {
            $this->objects[] = $result;
        }
    }





    /**
     * @return array
    */
    public function getObjects(): array
    {
         return $this->objects;
    }
}