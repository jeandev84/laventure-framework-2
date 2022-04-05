<?php
namespace Laventure\Component\Database\Migration\Common;

use Laventure\Component\Database\Migration\Contract\MigrationInterface;


/**
 * @AbstractMigration
*/
abstract class AbstractMigration implements MigrationInterface
{


    /**
     * Migration name
     *
     * @var string
    */
    private $name;





    /**
     * Migration path
     *
     * @var string
    */
    private $path;



    /**
     * AbstractMigration constructor.
     *
     * @param
    */
    public function __construct()
    {
        $this->name($this->calledClass()->getShortName())
             ->path($this->calledClass()->getFileName());
    }



    /**
     * Set migration name
     *
     * @param string $name
     * @return $this
    */
    public function name(string $name): self
    {
         $this->name = $name;

         return $this;
    }




    /**
     * Set migration path
     *
     * @param string $path
     * @return $this
    */
    public function path(string $path): self
    {
         $this->path = $path;

         return $this;
    }



    /**
     * @inheritDoc
    */
    public function getName(): string
    {
        return $this->name;
    }




    /**
     * @inheritDoc
    */
    public function getPath(): string
    {
        return $this->path;
    }



    /**
     * @return \ReflectionClass
    */
    private function calledClass(): \ReflectionClass
    {
        return new \ReflectionClass(get_called_class());
    }


    /**
     * @inheritDoc
    */
    public function up() {}




    /**
     * @inheritDoc
    */
    public function down() {}
}