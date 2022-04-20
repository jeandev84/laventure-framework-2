<?php
namespace Laventure\Foundation\Generator;


use Laventure\Component\FileSystem\FileSystem;
use Laventure\Foundation\Application;


/**
 * @EnvGenerator
*/
class EnvGenerator extends StubGenerator
{


    /**
     * @param Application $app
     * @param FileSystem $fileSystem
    */
    public function __construct(Application $app, FileSystem $fileSystem)
    {
          parent::__construct($app, $fileSystem);
    }




    /**
     * @return bool
    */
    public function generateEnv(): bool
    {
        $stubPath = $this->generateStubPath('env/template.stub');

        return $this->fileSystem->copy($stubPath, '.env');
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