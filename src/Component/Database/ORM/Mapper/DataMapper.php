<?php
namespace Laventure\Component\Database\ORM\Mapper;


/**
 * @DataMapper
*/
class DataMapper
{

      /**
       * Map object attributes
       *
       * @param $object
       * @return array
      */
      public function map($object): array
      {
            $attributes = [];

            $reflection = new \ReflectionObject($object);

            foreach ($reflection->getProperties() as $property) {
                $property->setAccessible(true);
                $attributes[$property->getName()] = $property->getValue($object);
            }

            return $attributes;
      }



      /**
       * @param array $attributes
       * @return void
      */
      protected function mapResolvedAttributes(array $attributes)
      {

      }
}
