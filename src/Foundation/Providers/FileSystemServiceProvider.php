<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @FileSystemServiceProvider
*/
class FileSystemServiceProvider extends ServiceProvider
{


    /**
     * @var string[]
    */
    protected $provides = [
        FileSystem::class => ['@fs']
    ];




    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(FileSystem::class, function () {
            return new FileSystem($this->app['path']);
        });
    }
}