<?php
namespace Laventure\Component\Database\Migration;


use Laventure\Component\Database\Connection\Contract\ConnectionInterface;
use Laventure\Component\Database\Migration\Contract\MigrationInterface;
use Laventure\Component\Database\Migration\Contract\MigratorInterface;
use Laventure\Component\Database\Query\QueryBuilder;
use Laventure\Component\Database\Schema\BluePrint\BluePrint;
use Laventure\Component\Database\Schema\Schema;


/**
 * @Migrator
*/
class Migrator implements MigratorInterface
{


    /**
     * Migrator table name
     *
     * @var string
    */
    protected $tableName = 'laventure_migrations';




    /**
     * @var ConnectionInterface
    */
    protected $connection;





    /**
     * Schema table
     *
     * @var Schema
    */
    protected $schema;




    /**
     * Query builder
     *
     * @var QueryBuilder
    */
    protected $queryBuilder;




    /**
     * Migration collection
     *
     * @var MigrationCollection
    */
    protected $migrations;



    /**
     * @var array
    */
    protected $messages = [];





    /**
     * Migrator constructor.
     *
     * @param ConnectionInterface $connection
    */
    public function __construct(ConnectionInterface $connection)
    {
           $this->schema       = new Schema($connection);
           $this->migrations   = new MigrationCollection();
           $this->queryBuilder = new QueryBuilder($connection, $this->tableName);
           $this->connection   = $connection;
    }




    /**
     * @param string $tableName
     * @return $this
    */
    public function table(string $tableName): self
    {
         $this->tableName = $tableName;

         $this->queryBuilder->table($tableName);

         return $this;
    }





    /**
     * @inheritDoc
    */
    public function getTableName()
    {
         return $this->tableName;
    }



    /**
     * Add migration
     *
     * @param MigrationInterface $migration
     * @return $this
    */
    public function addMigration(MigrationInterface $migration): self
    {
         $this->migrations->add($migration);

         return $this;
    }






    /**
     * Add migrations
     *
     * @param MigrationInterface[]  $migrations
     * @return void
    */
    public function addMigrations(array $migrations)
    {
         foreach ($migrations as $migration) {
             $this->addMigration($migration);
         }
    }




    /**
     * @inheritDoc
    */
    public function getMigrations()
    {
         return $this->migrations->getMigrations();
    }




    /**
     * @inheritDoc
    */
    public function getOldMigrations()
    {
         return $this->queryBuilder
                     ->select([$this->getReferenceColumn()])
                     ->from($this->getTableName())
                     ->getQuery()
                     ->getArrayColumns();
    }





    /**
     * @inheritDoc
    */
    public function createMigrationTable()
    {
         if (! $this->hasMigrationTable()) {

             $this->schema->create($this->getTableName(), function (BluePrint $table) {
                 $table->increments('id');
                 $table->string('version');
                 $table->datetime('executed_at');
                 $table->boolean('executed');
             });

             $this->log("Migration version table ({$this->getTableName()}) successfully installed!");
         }

         $this->log("Migration version table ({$this->getTableName()}) already installed.");
    }



    /**
     * @return bool
    */
    public function hasMigrationTable(): bool
    {
         return \in_array($this->getTableName(), $this->schema->showTables());
    }




    /**
     * @inheritDoc
    */
    public function migrate(): bool
    {
         return $this->connection->transaction(function () {

              $this->createMigrationTable();

              $this->migrations->setOldMigrations($this->getOldMigrations());

              $newMigrations = $this->migrations->getNewMigrations();

              $this->up($newMigrations);
         });
    }




    /**
     * Run method up of migrations
     *
     * @param MigrationInterface[] $migrations
     * @return void
    */
    public function up(array $migrations)
    {
        if (! empty($migrations)) {
            foreach ($migrations as $migration) {
                $this->saveMigration($migration);
                $this->logWithTimestamp("Migration {$migration->getName()} successfully applied.");
            }
        } else {
            $this->log("Migrations already applied.");
        }
    }




    /**
     * @param MigrationInterface $migration
     * @return void
    */
    public function saveMigration(MigrationInterface $migration)
    {
          $migration->up();

          $this->queryBuilder->insert($this->getAttributes($migration));
    }





    /**
     * @inheritDoc
    */
    public function rollback(): bool
    {
        return $this->connection->transaction(function () {

              $this->down($this->getMigrations());

              if ($this->hasMigrationTable()) {
                  $this->schema->truncateTable($this->getTableName());
              }
        });
    }





    /**
     * Drop all created tables.
     *
     * @param MigrationInterface[]
    */
    public function down(array $migrations)
    {
        foreach ($migrations as $migration) {
            $migration->down();
            $this->logWithTimestamp("Migration {$migration->getName()} successfully rollback.");
        }
    }




    /**
     * @inheritDoc
    */
    public function reset()
    {
         $this->rollback();

         if ($this->hasMigrationTable()) {
             $this->schema->dropIfExists($this->getTableName());
         }
    }




    /**
     * @inheritDoc
    */
    public function clean()
    {
         $this->reset();

         $this->migrations->removeMigrationFiles();
    }




    /**
     * Make attributes migrator table for version migrations
     *
     * @param MigrationInterface $migration
     * @return array
    */
    protected function getAttributes(MigrationInterface $migration): array
    {
        return [
            'version'     => $migration->getName(),
            'executed_at' => (new \DateTime())->format('Y-m-d H:i:s'),
            'executed'    => 1
        ];
    }



    /**
     * Reference column for check record from migrator table
     *
     * @return string
    */
    public function getReferenceColumn(): string
    {
        return 'version';
    }



    /**
     * @param $message
     * @return void
    */
    public function log($message)
    {
        $this->messages[] = $message;
    }



    /**
     * @param $message
     * @return void
    */
    public function logWithTimestamp($message)
    {
        $this->log(sprintf('%s %s', '['. date('Y-m-d H:i:s') .'] ', $message));
    }




    /**
     * @return array
    */
    public function getLogMessages(): array
    {
        return $this->messages;
    }

}