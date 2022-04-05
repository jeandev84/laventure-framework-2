<?php
namespace Laventure\Component\Database\Connection\Drivers\PDO\Connectors;


use Laventure\Component\Database\Connection\Drivers\PDO\PdoConnection;



/**
 * @OracleConnection
*/
class OracleConnection extends PdoConnection
{
    /**
     * @return string
    */
    public function getName(): string
    {
        return 'oci';
    }
}