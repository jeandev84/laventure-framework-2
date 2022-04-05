<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Config\Config;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Routing\Generator\UrlGenerator;
use Laventure\Component\Routing\Generator\UrlGeneratorInterface;
use Laventure\Component\Routing\Router;


/**
 * @UrlGeneratorServiceProvider
*/
class UrlGeneratorServiceProvider extends ServiceProvider
{


    /**
     * @var \string[][]
    */
    protected $provides = [
        UrlGenerator::class => [UrlGeneratorInterface::class]
    ];


    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(UrlGenerator::class, function (Router $router, Config $config) {
              return new UrlGenerator($router, $config['app.url']);
         });
    }
}