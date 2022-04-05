<?php
namespace Laventure\Foundation\Providers;


use Laventure\Component\Console\Console;
use Laventure\Component\Console\ConsoleInterface;
use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Foundation\Console\Application;
use Laventure\Foundation\Loaders\CommandLoader;


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
        $loader->setLocatePath($this->app['config']['app.directories.commands'])
               ->setResourcePattern($this->app['config']['app.resources.commands'])
               ->setNamespace($this->app['config']['app.namespaces.commands'])
               ->setLoadPaths($this->app['config']['app.paths.routes.console']);

        $loader->loadCommands($this->app['@fs']);
        $loader->loadPaths($this->app['@fs']);

        $this->app->instance(CommandLoader::class, $loader);
    }

}