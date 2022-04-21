<?php
namespace Laventure\Foundation\Provider;


use Laventure\Component\Container\ServiceProvider\ServiceProvider;
use Laventure\Component\Templating\Renderer\Renderer;
use Laventure\Component\Templating\Renderer\RendererInterface;


/**
 * @ViewServiceProvider
*/
class ViewServiceProvider extends ServiceProvider
{


    /**
     * @var string[][]
    */
    protected $provides = [
          Renderer::class => ['view', RendererInterface::class]
    ];



    /**
     * @inheritDoc
    */
    public function register()
    {
         $this->app->singleton(Renderer::class, function () {

              $resource  = $this->locateResourcePath();

              $renderer  = new Renderer($resource);
              $renderer->extension($this->getExtension())
                       ->cache($this->getCacheStatus())
                       ->cacheDir($this->getCachePath())
                       ->compress($this->getCompressStatus());

              return $renderer;
         });
    }




    /**
     * @return mixed
    */
    private function getResourcePath()
    {
        return $this->app['config']['view.path'];
    }



    /**
     * @return string
    */
    private function locateResourcePath(): string
    {
         return $this->app['@fs']->locate($this->getResourcePath());
    }



    /**
     * @return string
    */
    private function getCacheDir(): string
    {
        return $this->app['config']['view.cacheDir'];
    }




    /**
     * @return string
    */
    private function getCachePath(): string
    {
        return $this->app['@fs']->locate($this->getCacheDir());
    }




    /**
     * @return bool
    */
    private function getCompressStatus(): bool
    {
        return $this->app['config']['view.compress'];
    }



    /**
     * @return bool
    */
    private function getCacheStatus(): bool
    {
        return $this->app['config']['view.cache'];
    }




    /**
     * @return string
    */
    private function getExtension(): string
    {
         return $this->app['config']['view.extension'];
    }
}