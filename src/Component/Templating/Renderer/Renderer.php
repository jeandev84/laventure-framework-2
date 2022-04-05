<?php
namespace Laventure\Component\Templating\Renderer;


/**
 * @Render
*/
class Renderer implements RendererInterface
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
    protected $pathExtension = 'php';





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
     * @var string
    */
    protected $cacheDir;




    /**
     * @var bool
    */
    protected $cached = true;




    /**
     * Renderer constructor
     *
     * @param string|null $resource
     * @param string|null $layout
    */
    public function __construct(string $resource = null, string $layout = null)
    {
          if ($resource) {
              $this->resourcePath($resource);
          }

          if ($layout) {
              $this->withLayout($layout);
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
    public function resourcePath(string $path): Renderer
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
     * @param $layout
     * @return $this
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
         $this->cacheDir = $path;

         $this->cacheManager->cacheDir($path);

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
     * @inheritDoc
    */
    public function render(string $template, array $arguments = [])
    {
        $content = $this->renderTemplate($this->loadTemplatePath($template));

        if ($this->layout) {
            $content = $this->renderLayout($content);
        }

        $content = $this->renderCache($template, $content, $arguments);

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
            trigger_error("Template file '{$template}' does not exist.");
        }

        ob_start();
        require $template;
        return ob_get_clean();
    }




    /**
     * @param $templateContent
     * @return string|null
    */
    public function renderLayout($templateContent): ?string
    {
        $templateContent = $this->surroundContent($templateContent);
        $layoutPath      = sprintf('%s%s', $this->getLayout(), $this->getPathExtension());
        $layoutContent   = $this->renderTemplate($this->loadPath($layoutPath));

        return str_replace("{{ content }}", $templateContent, $layoutContent);
    }





    /**
     * @param $template
     * @param $content
     * @param array $arguments
     * @return false|string
    */
    public function renderCache($template, $content, array $arguments = [])
    {
        if (! $this->cached) {
            return $content;
        }

        $content = $this->renderTags->replaceTags($content);

        if(! $this->cacheManager->cache($template, $content)) {
            trigger_error("Something went wrong for caching template '{$template}'");
        }

        $cachePath = $this->cacheManager->loadCachePath($template);

        return $this->renderTemplate($cachePath, $arguments);
    }




    /**
     * @param $template
     * @return string
    */
    public function loadTemplatePath($template): string
    {
          $extension = pathinfo($template, PATHINFO_EXTENSION);
          $template  = str_replace('.'. $extension, '', $template);

          return $this->loadPath(sprintf('%s%s', $template, $this->getPathExtension()));
    }




    /**
     * Get path extension
     *
     * @return string
     */
    public function getPathExtension(): string
    {
        return sprintf('.%s', $this->pathExtension);
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