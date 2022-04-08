<?php
namespace Laventure\Foundation\Generator;


use Laventure\Component\FileSystem\FileSystem;


/**
 * @EnvGenerator
*/
class EnvGenerator
{


    /**
     * @var string
    */
    protected $fileSystem;



    /**
     * @param FileSystem $fileSystem
    */
    public function __construct(FileSystem $fileSystem)
    {
         $this->fileSystem = $fileSystem;
    }





    /**
     * @return bool
    */
    public function generateEnv(): bool
    {
        return $this->fileSystem->copy('.env.example', '.env');
    }




    /**
     * Change dynamically value given key inside env file
     *
     * @param string $key
     * @param string $value
     * @return bool
    */
    public function writeToEnv(string $key, string $value): bool
    {
        $assigned   = sprintf("{$key}=%s", $value);
        $previousContent = $this->fileSystem->read('.env');
        $newContent = preg_replace("/{$key}=(.*)/", $assigned, $previousContent);
        $this->fileSystem->remove('.env');
        return $this->fileSystem->write('.env', $newContent);
    }
}