<?php
namespace Laventure\Component\Database\Migration;



use Laventure\Component\Database\Migration\Contract\MigrationCollectionInterface;
use Laventure\Component\Database\Migration\Contract\MigrationInterface;


/**
 * @MigrationCollection
*/
class MigrationCollection implements MigrationCollectionInterface
{


    /**
     * Collect migration by given name
     *
     * @var MigrationInterface[]
    */
    protected $migrations = [];





    /**
     * Collect previous name of migrations
     *
     * @var string[]
    */
    protected $oldMigrations = [];




    /**
     * Collect migration paths
     *
     * @var string[]
    */
    protected $migrationPaths = [];





    /**
     * MigrationCollection constructor
     *
     * @param MigrationInterface[] $migrations
    */
    public function __construct(array $migrations = [])
    {
           if ($migrations) {
               $this->addMigrations($migrations);
           }
    }




    /**
     * Add a migration
     *
     * @param MigrationInterface $migration
     * @return $this
    */
    public function add(MigrationInterface $migration): self
    {
         $this->migrations[$migration->getName()]     = $migration;
         $this->migrationPaths[$migration->getName()] = $migration;

         return $this;
    }




    /**
     * Add collection migrations
     *
     * @param MigrationInterface[] $migrations
     * @return $this
    */
    public function addMigrations(array $migrations): self
    {
         foreach ($migrations as $migration) {
             $this->add($migration);
         }

         return $this;
    }





    /**
     * Add previous migrations
     *
     * @param string[] $migrations
     * @return void
    */
    public function setOldMigrations(array $migrations)
    {
          $this->oldMigrations = $migrations;
    }




    /**
     * @inheritDoc
    */
    public function getMigrations()
    {
         return $this->migrations;
    }



    /**
     * @inheritDoc
    */
    public function getNewMigrations(): array
    {
         $migrations = [];

         foreach ($this->getMigrations() as $migration) {
              if (! $this->isOldMigrations($migration->getName())) {
                   $migrations[] = $migration;
              }
         }

         return $migrations;
    }





    /**
     * Get old migrations
     *
     * @return MigrationInterface[]
    */
    public function getOldMigrations(): array
    {
         return $this->oldMigrations;
    }




    /**
     * Determine if the given name is in previous migration names
     *
     * @param string $name
     * @return bool
    */
    public function isOldMigrations(string $name): bool
    {
         return in_array($name, $this->oldMigrations);
    }




    /**
     * @inheritDoc
    */
    public function getMigration($name)
    {
         return $this->migrations[$name] ?? null;
    }




    /**
     * @inheritDoc
    */
    public function removeMigration($name)
    {
         if (! $this->has($name)) {
             trigger_error("Migration '{$name}' is not in migration collections.");
         }


         unset($this->migrations[$name]);
         unset($this->migrationPaths[$name]);
    }



    /**
     * Determine if the given name has in collection migrations
     *
     * @param $name
     * @return bool
    */
    public function has($name): bool
    {
         return isset($this->migrations[$name]);
    }





    /**
     * Remove file concrete migration
     *
     * @param string $name
     * @return bool
    */
    public function removeMigrationFile(string $name): bool
    {
         if ($this->has($name)) {
             return @unlink($this->getMigration($name)->getPath());
         }

         return false;
    }



    /**
     * Remove all migration files
     *
     * @return void
    */
    public function removeMigrationFiles()
    {
         foreach ($this->getMigrations() as $migration) {
              $this->removeMigrationFile($migration->getName());
         }
    }




    /**
     * @inheritDoc
    */
    public function clearMigrations()
    {
         $this->removeMigrationFiles();
         $this->removeMigrations();
    }




    /**
     * @inheritDoc
    */
    public function removeMigrations()
    {
        foreach ($this->getMigrations() as $migration) {
             $this->removeMigration($migration->getName());
        }
    }
}