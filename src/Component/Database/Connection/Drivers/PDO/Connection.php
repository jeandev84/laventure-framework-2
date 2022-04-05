<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO;



use Exception;
use Laventure\Component\Database\Connection\Drivers\PDO\Exception\PdoConnectionException;
use PDO;
use PDOException;


/**
 * @Connection
*/
class Connection
{

    /**
     * @var array
    */
    protected static $defaultOptions = [
        PDO::ATTR_PERSISTENT          => true,
        PDO::ATTR_EMULATE_PREPARES    => 0,
        PDO::ATTR_DEFAULT_FETCH_MODE  => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE             => PDO::ERRMODE_EXCEPTION
    ];



    /**
     * Make PDO Connection
     *
     * @param array $config
     * @return PDO|bool
    */
    public static function make(array $config)
    {
        try {

            $pdo = new PDO($config['dsn'], $config['username'], $config['password']);

            $config['options'][] = sprintf("SET NAMES '%s'", $config['charset'] ?? 'utf8');

            foreach ($config['options'] as $option) {
                $pdo->exec($option);
            }

            foreach (static::$defaultOptions as $key => $value) {
                $pdo->setAttribute($key, $value);
            }

            return $pdo;

        } catch (PDOException $e) {

            return trigger_error($e->getMessage());
        }
    }



    /**
     * @param $connection
     * @return bool
    */
    public static function has($connection): bool
    {
        return \in_array($connection, PDO::getAvailableDrivers());
    }
}