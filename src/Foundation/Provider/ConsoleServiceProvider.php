<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Console\Console;
use Laventure\Component\Console\ConsoleInterface;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Foundation\Console\Application;
use Laventure\Foundation\Loader\CommandLoader;


/**
 * @ConsoleServiceProvider
*/
class ConsoleServiceProvider extends ServiceProvider
{

    /**
     * @var array
    */
    protected $provides = [
        Console::class       => ['console', ConsoleInterface::class],
        CommandLoader::class => ['@command.loader']
    ];


    /**
     * @inheritDoc
    */
    public function register()
    {
        $this->app->singleton(Console::class, function () {
            return new Application($this->app);
        });

    }



    /**
     * @return void
    */
    public function terminate()
    {
        $loader = new CommandLoader($this->app, $this->app[Console::class]);
        $loader->setLocatePath('app/Console/Command')
               ->setResourcePattern('app/Console/Command/*.php')
               ->setNamespace('App\\Console\\Command')
               ->setLoadPaths('/config/routes/console.php');

        $loader->loadCommands($this->app['@fs']);
        $loader->loadPaths($this->app['@fs']);

        $this->app->instance(CommandLoader::class, $loader);
    }

}