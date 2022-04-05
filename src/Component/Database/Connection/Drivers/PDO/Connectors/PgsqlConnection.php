<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;



/**
 * @PgsqlConnection
*/
class PgsqlConnection extends PdoConnection
{

    /**
     * Get connection name
     *
     * @return string
    */
    public function getName(): string
    {
        return 'pgsql';
    }


    /**
     * Create database
     *
     * @return bool
    */
    public function createDatabase(): bool
    {
        $sql = sprintf("SELECT 'CREATE DATABASE %s' 
            WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '%s')",
            $this->config['database'],
            $this->config['database']
        );

        return $this->exec($sql);
    }



    /**
     * Drop database
     *
     * @return mixed
    */
    public function dropDatabase()
    {
        $sql =  sprintf("SELECT pg_terminate_backend(pid) 
                   FROM pg_stat_activity 
                   WHERE pid <> pg_backend_pid() AND datname = '%s';",
            $this->config['database']
        );

        return $this->exec($sql);
    }



    /**
     * @inheritDoc
    */
    public function showDatabases(): array
    {
           $databases = $this->query('SELECT datname FROM pg_database;')
                            ->fetchMode(\PDO::FETCH_ASSOC)
                            ->getResult();


           $collections = [];

           foreach ($databases as $results) {
                foreach ($results as $key => $value) {
                     if ($key === 'datname') {
                          $collections[] = $value;
                     }
                }
           }

           return $collections;
    }




    /**
     * @inheritDoc
     */
    public function showTables()
    {
        $tables = [];

        foreach ($this->showInformationSchema() as $information) {
            $tables[] = $information->tablename;
        }

        return $tables;
    }




    /**
     * @return mixed
    */
    public function showInformationSchema()
    {
        $sql = "SELECT * FROM pg_catalog.pg_tables 
                WHERE schemaname != 'pg_catalog' 
                AND schemaname != 'information_schema';
                ";

        return $this->query($sql)->getResult();
    }

}