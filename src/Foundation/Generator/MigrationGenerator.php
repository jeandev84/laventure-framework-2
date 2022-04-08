<?php
namespace Laventure\Foundation\Generator;



use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;
use Laventure\Foundation\Loader\MigrationLoader;


/**
 * @MigrationGenerator
*/
class MigrationGenerator extends StubGenerator
{


    /**
     * @var MigrationLoader
    */
    protected $loader;




    /**
     * @param Application $app
     * @param FileSystem $fileSystem
     * @param MigrationLoader $loader
    */
    public function __construct(
        Application $app,
        FileSystem $fileSystem,
        MigrationLoader $loader
    )
    {
        parent::__construct($app, $fileSystem);
        $this->loader = $loader;
    }


    /**
     * Generate a migration file from stub.
     *
     * @param string|null $migrationName
     * @return bool
    */
    public function generate(string $migrationName): bool
    {
        $stub = $this->generateStub('migration/template', [
            'MigrationNamespace'  => $this->loadNamespace(),
            'MigrationClass'      => $migrationName,
            'tableName'           => 'tableName'
        ]);


        return $this->writeTo($this->loadMigrationPath($migrationName), $stub);
    }




    /**
     * @param string|null $migrationName
     * @return string
    */
    public function loadNamespace(string $migrationName = null): string
    {
        return $this->loader->loadNamespace($migrationName);
    }




    /**
     * @param string $migrationName
     * @return string
    */
    public function loadMigrationPath(string $migrationName): string
    {
        return $this->loader->loadLocatePath($migrationName);
    }
}