<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Config\Config;
use Laventure\Component\Config\Loaders\ArrayLoader;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\FileSystem\FileSystem;


/**
 * @ConfigurationServiceProvider
*/
class ConfigurationServiceProvider extends ServiceProvider
{


    /**
     * @var \string[][]
    */
    protected $provides = [
        Config::class => ['config']
    ];




    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Config::class, function (FileSystem $fs) {

              $config = new Config();

              $config->load([
                  $this->loadConfigParams($fs)
              ]);

              return $config;
         });
    }



    /**
     * @param FileSystem $fs
     * @return ArrayLoader
    */
    protected function loadConfigParams(FileSystem $fs): ArrayLoader
    {
         $data = $fs->collection('/config/params/*.php')->getPaths();

         return new ArrayLoader($data);
    }

}