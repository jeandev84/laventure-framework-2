<?php
namespace Laventure\Component\Templating\Renderer;



/**
 * @Render
*/
class Renderer implements RendererInterface, RenderLayoutInterface
{


    /**
     * Renderer resource path
     *
     * @var string
    */
    protected $resource;




    /**
     * View layout
     *
     * @var mixed
    */
    protected $layout;




    /**
     * @var string
    */
    protected $extension = 'php';





    /**
     * @var RenderCacheManager
    */
    protected $cacheManager;




    /**
     * @var RenderCompressor
    */
    protected $compressor;





    /**
     * @var RenderTags
    */
    protected $renderTags;




    /**
     * @var bool
    */
    protected $compressed = false;




    /**
     * @var bool
    */
    protected $cached = true;





    /**
     * Renderer constructor
     *
     * @param string|null $resource
    */
    public function __construct(string $resource = null)
    {
          if ($resource) {
              $this->resource($resource);
          }

          $this->cacheManager = new RenderCacheManager($resource);
          $this->compressor   = new RenderCompressor();
          $this->renderTags   = new RenderTags();
    }




    /**
     * @param bool $cached
     * @return $this
    */
    public function cache(bool $cached): self
    {
         $this->cached = $cached;

         return $this;
    }




    /**
     * @param string $path
     * @return $this
    */
    public function resource(string $path): Renderer
    {
         $this->resource = rtrim($path, '\\/');

         return $this;
    }






    /**
     * @return string
    */
    public function getResourcePath(): string
    {
         return $this->resource;
    }





    /**
     * @inheritDoc
    */
    public function withLayout($layout): self
    {
        $this->layout = $layout;

        return $this;
    }




    /**
     * @return mixed
    */
    public function getLayout(): string
    {
        return $this->layout;
    }





    /**
     * Set cache directory
     *
     * @param string $path
     * @return $this
    */
    public function cacheDir(string $path): self
    {
         if ($this->cached) {
             $this->cacheManager->cacheDir($path);
         }

         return $this;
    }




    /**
     * Compress html content very import for loading quickly site
     *
     * @param bool $compressed
     * @return $this
    */
    public function compress(bool $compressed): self
    {
        $this->compressed = $compressed;

        return $this;
    }





    /**
     * @param string $extension
     * @return $this
    */
    public function extension(string $extension): self
    {
        $this->extension = trim($extension, '.');

        return $this;
    }





    /**
     * Get path extension
     *
     * @return string
    */
    public function getExtension(): string
    {
        return sprintf('.%s', $this->extension);
    }





    /**
     * @inheritDoc
    */
    public function render(string $template, array $arguments = [])
    {
          $content = $this->renderTemplate($this->loadTemplate($template));
        
          if ($this->layout) {
              $content = $this->renderLayout($content);
          }

          if ($this->cached) {
              $content = $this->renderCache($template, $content, $arguments);
          }

          if ($this->compressed) {
              $content = $this->compressor->compress($content);
          }

          return $content;
    }





    /**
     * @param $template
     * @param array $arguments
     * @return false|string
    */
    public function renderTemplate($template, array $arguments = [])
    {
        extract($arguments, EXTR_SKIP);

        if (! is_file($template)) {
            trigger_error("View file : {$template} does not exist.". __METHOD__);
        }

        ob_start();
        require $template;
        return ob_get_clean();
    }




    /**
     * @inheritDoc
    */
    public function renderLayout($content): ?string
    {
        $content        = PHP_EOL. $content . PHP_EOL;
        $layoutPath     = sprintf('%s%s', $this->getLayout(), $this->getExtension());
        $layoutContent  = $this->renderTemplate($this->loadPath($layoutPath));

        return str_replace("{{ content }}", $content, $layoutContent);
    }




    /**
     * @param $content
     * @return mixed
    */
    public function replaceTags($content)
    {
         return $this->renderTags->replaceTags($content);
    }







    /**
     *
     * @param $template
     * @param $content
     * @param array $arguments
     * @return false|string
    */
    public function renderCache($template, $content, array $arguments = [])
    {
        $content = $this->replaceTags($content);

        if(! $this->cacheManager->cacheTemplate($template, $content)) {
            return false;
        }

        $cachePath = $this->cacheManager->loadTemplateCachePath($template);

        return $this->renderTemplate($cachePath, $arguments);
    }




    /**
     *
     * @param $path
     * @return false|int
    */
    public function cacheIncludePath($path)
    {
         $content = $this->renderTemplate($this->loadPath($path));
         $content = $this->replaceTags($content);
         
         return $this->cacheManager->cacheIncludeTemplate($path, $content);
    }




    /**
     * @param $path
     * @return string
    */
    public function loadIncludeCache($path): string
    {
         return $this->cacheManager->loadIncludeTemplate($path);
    }





    /**
     * @param $template
     * @return string
    */
    public function loadTemplate($template): string
    {
          $extension = pathinfo($template, PATHINFO_EXTENSION);
          $template  = str_replace('.'. $extension, '', $template);

          return $this->loadPath(sprintf('%s%s', $template, $this->getExtension()));
    }





    /**
     * @param string $path
     * @return string
    */
    public function loadPath(string $path): string
    {
        return $this->resource . DIRECTORY_SEPARATOR . $this->resolvePath($path);
    }




    /**
     * @param $path
     * @return string
     */
    protected function resolvePath($path): string
    {
        return trim($path, '\\/');
    }




    /**
     * @param $content
     * @return string
    */
    public function surroundContent($content): string
    {
        return PHP_EOL. $content . PHP_EOL;
    }


}