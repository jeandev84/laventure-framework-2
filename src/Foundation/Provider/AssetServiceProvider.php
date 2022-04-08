<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Config\Config;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Templating\Asset\Asset;
use Laventure\Component\Templating\Asset\AssetInterface;


/**
 * @AssetServiceProvider
*/
class AssetServiceProvider extends ServiceProvider
{

    /**
     * @var string[][]
    */
    protected $provides = [
        Asset::class => ['assets', AssetInterface::class]
    ];




    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Asset::class, function (Config $config) {
              return new Asset($config['app.url']);
         });
    }
}