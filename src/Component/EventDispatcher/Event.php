<?php
namespace Laventure\Component\EventDispatcher;



use ReflectionObject;



/**
 * @Event
*/
abstract class Event
{


    /**
     * @var string
    */
    protected $name;




    /**
     * @return string
    */
    public function getName(): string
    {
        return $this->name ?? (new ReflectionObject($this))->getShortName();
    }



    /**
     * @param string $name
     * @return void
    */
    public function setName(string $name)
    {
        $this->name = $name;
    }
}