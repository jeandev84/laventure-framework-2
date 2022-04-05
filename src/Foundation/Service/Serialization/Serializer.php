<?php
namespace Laventure\Foundation\Service\Serialization;


/**
 * @Serializer
 */
class Serializer
{


    /**
     * @var array
    */
    protected static $cache = [];




    /**
     * @param $name
     * @param $context
     * @return void
    */
    public static function serialize($name, $context)
    {
        self::$cache[$name] = serialize($context);
    }




    /**
     * @param $name
     * @return bool
    */
    public static function serialised($name): bool
    {
        return isset(self::$cache[$name]);
    }




    /**
     * @param $name
     * @return mixed
    */
    public static function deserialize($name)
    {
        if(! self::serialised($name)) {
            return false;
        }

        return unserialize(self::$cache[$name]);
    }
}