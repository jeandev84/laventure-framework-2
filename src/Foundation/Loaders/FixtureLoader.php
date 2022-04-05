<?php
namespace Laventure\Foundation\Loaders;

use Laventure\Component\Container\Container;
use Laventure\Component\Database\ORM\Manager\EntityManager;
use Laventure\Component\Database\ORM\Manager\Fixtures\Contract\Fixture;
use Laventure\Component\Database\ORM\Manager\Fixtures\FixtureManager;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Loader;



/**
 * @FixtureLoader
*/
class FixtureLoader extends Loader
{



        /**
         * @var FixtureManager
        */
        protected $fixture;




        /**
         * @param Container $app
         * @param FixtureManager $fixture
        */
        public function __construct(Container $app, FixtureManager $fixture)
        {
            parent::__construct($app);
            $this->fixture = $fixture;
        }





       /**
        * @param FileSystem $fileSystem
        * @return void
       */
       public function addFixtures(FileSystem $fileSystem)
       {
             foreach ($this->getFileNames($fileSystem) as $fixtureName) {

                 $fixtureClass = $this->loadNamespace($fixtureName);
                 $fixture = $this->app->get($fixtureClass);

                 if ($fixture instanceof Fixture) {
                      $this->fixture->addFixture($fixture);
                 }
             }
       }




       /**
        * @return void
       */
       public function loadFixtures()
       {
            $this->fixture->loadFixtures();
       }



       /**
        * @return array
       */
       public function getLogMessages(): array
       {
            return $this->fixture->getLogMessages();
       }
}