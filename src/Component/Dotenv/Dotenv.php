<?php
namespace Laventure\Component\Dotenv;


/**
 * @Dotenv
*/
class Dotenv
{

    /**
     * @var Dotenv
    */
    protected static $instance;



    /**
     * @var string
     */
    protected $root;



    /**
     * @var Env
    */
    protected $env;



    /**
     * @var string
    */
    protected $filename = '.env';



    /**
     * Dotenv constructor.
     *
     * @param string $root
    */
    public function __construct(string $root)
    {
          $this->root = $root;
          $this->env  = new Env();
    }



    /**
     * @param string $resource
     * @return Dotenv
    */
    public static function create(string $resource): Dotenv
    {
        if (! static::$instance) {
            static::$instance = new static($resource);
        }

        return static::$instance;
    }



    /**
     * Set environment file name
     *
     * @param string $filename
     * @return $this
    */
    public function with(string $filename): self
    {
          $this->filename = $filename;

          return $this;
    }


    /**
     * @return bool
    */
    public function load(): bool
    {
        if ($environs = $this->loadEnvironments($this->filename)) {
            foreach ($environs as $environ) {
                $this->env->put($environ);
            }
            return true;
        }

        return false;
    }





    /**
     * @param string $filename
     * @return array
    */
    public function loadEnvironments(string $filename): array
    {
        $filename = $this->root . DIRECTORY_SEPARATOR. $filename;

        if(! file_exists($filename)) {
            return [];
        }

        return file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    }
}