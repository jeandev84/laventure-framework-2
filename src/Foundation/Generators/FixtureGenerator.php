<?php
namespace Laventure\Foundation\Generators;



use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Loaders\FixtureLoader;



/**
 * @FixtureGenerator
*/
class FixtureGenerator extends StubGenerator
{


    /**
     * @var FixtureLoader
    */
    protected $loader;




    /**
     * @param Application $app
     * @param FileSystem $fileSystem
     * @param FixtureLoader $loader
    */
    public function __construct(
        Application $app,
        FileSystem $fileSystem,
        FixtureLoader $loader
    )
    {
         parent::__construct($app, $fileSystem);
         $this->loader = $loader;
    }




    /**
     * @param string $fixtureName
     * @return bool
    */
    public function generateFixture(string $fixtureName): bool
    {
           $fixtureName = str_replace('Fixture', '', $fixtureName);
           $fixtureClass = sprintf('%sFixture', ucfirst($fixtureName));

           $stub = $this->generateStub('orm/fixture/template', [
                 'FixtureNamespace' => $this->loader->getNamespace(),
                 'FixtureClass'     => $fixtureClass
           ]);

           // app/Fixtures/ProductFixture.php
           return $this->writeTo($this->loader->loadLocatePath($fixtureClass), $stub);
    }
}