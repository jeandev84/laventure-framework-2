<?php
namespace Laventure\Component\Templating\Renderer;


/**
 * @RenderCacheManager
 */
class RenderCacheManager
{


      /**
       * @var string
      */
      protected $cacheDir;




      /**
       * @param string|null $cacheDir
      */
      public function __construct(string $cacheDir = null)
      {
            if ($cacheDir) {
                $this->cacheDir($cacheDir);
            }
      }




      /**
       * @param $cacheDir
       * @return void
      */
      public function cacheDir($cacheDir)
      {
            $this->cacheDir = $cacheDir;
      }




      /**
       * @return string
      */
      public function getCacheDir(): string
      {
            return rtrim($this->cacheDir, '\\/');
      }




      /**
       * @param string $template
       * @param string $content
       * @return bool
      */
      public function cache(string $template, string $content): bool
      {
          $cachePath = $this->loadCachePath($template);

          $cacheDirname = pathinfo($cachePath, PATHINFO_DIRNAME);

          if (! is_dir($cacheDirname)) {
              @mkdir($cacheDirname, 0777, true);
          }

          if(! touch($cachePath)) {
              trigger_error("Something went wrong for generation file '{$cachePath}' inside.", __METHOD__);
          }

          return file_put_contents($cachePath,  $content);
      }




      /**
       * @param string $template
       * @return string
      */
      public function loadCachePath(string $template): string
      {
           return sprintf('%s%s%s', $this->getCacheDir(), DIRECTORY_SEPARATOR, md5($template). '.php');
      }



      /**
       * @param string $template
       * @return void
      */
      public function removeCachePath(string $template)
      {
            @unlink($this->loadCachePath($template));
      }




      /**
       * @param string $template
       * @return bool
      */
      public function existCachePath(string $template): bool
      {
           return file_exists($this->loadCachePath($template));
      }

}