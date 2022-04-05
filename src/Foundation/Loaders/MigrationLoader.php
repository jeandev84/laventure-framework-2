<?php
namespace Laventure\Foundation\Loaders;


use Laventure\Component\Container\Container;
use Laventure\Component\Database\Migration\Migration;
use Laventure\Component\Database\Migration\Migrator;
use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Loader;


/**
 * @MigrationLoader
*/
class MigrationLoader extends Loader
{

    /**
     * @var Migrator
    */
    protected $migrator;




    /**
     * @param Container $app
     * @param Migrator $migrator
    */
    public function __construct(Container $app, Migrator $migrator)
    {
        parent::__construct($app);
        $this->migrator = $migrator;
    }




    /**
     * Load migrations
     *
     * @param FileSystem $fileSystem
     * @return void
    */
    public function loadMigrations(FileSystem $fileSystem)
    {
        foreach ($this->getFileNames($fileSystem) as $migrationName) {

            $migrationClass = $this->loadNamespace($migrationName);
            $migration = $this->app->get($migrationClass);

            if ($migration instanceof Migration) {
                $this->migrator->addMigration($migration);
            }
        }
    }
}