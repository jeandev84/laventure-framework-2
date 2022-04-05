<?php
namespace Laventure\Component\Database\ORM\Manager\Fixtures;


use Laventure\Component\Database\ORM\Manager\Contract\ObjectManager;
use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\ORM\Manager\Fixtures\Contract\Fixture;


/**
 * @FixtureManager
*/
class FixtureManager
{


       /**
        * @var EntityManager
       */
       protected $em;



       /**
        * Fixture collection
        *
        * @var Fixture[]
       */
       protected $fixtures = [];




       /**
        * Storage message log
        *
        * @var array
       */
       protected $messageLog = [];





       /**
        * FixtureManager constructor.
        *
        * @param EntityManager $em
       */
       public function __construct(EntityManager $em)
       {
             $this->em = $em;
       }




       /**
        * Add Fixture
        *
        * @param Fixture $fixture
        * @return void
       */
       public function addFixture(Fixture $fixture)
       {
            $this->fixtures[] = $fixture;
       }




       /**
        * Add Fixtures
        *
        * @param Fixture[] $fixtures
        * @return void
       */
       public function addFixtures(array $fixtures)
       {
            foreach ($fixtures as $fixture) {
                  $this->addFixture($fixture);
            }
       }



       /**
        * Get fixtures
        *
        * @return Fixture[]
       */
       public function getFixtures(): array
       {
           return $this->fixtures;
       }




       /**
        * Load fixtures
        *
        * @return void
       */
       public function loadFixtures()
       {
           foreach ($this->getFixtures() as $fixture) {
               $fixture->load($this->em);
               $this->log($this->printLogMessage($fixture));
           }
       }




       /**
        * @param string $message
        * @return void
       */
       public function log(string $message)
       {
            $this->messageLog[] = $message;
       }




       /**
        * @return array
       */
       public function getLogMessages(): array
       {
           return $this->messageLog;
       }




       /**
        * @param Fixture $fixture
        * @return string
       */
       private function printLogMessage(Fixture $fixture): string
       {
           return sprintf("Fixture %s successfully loaded.", get_class($fixture));
       }

}