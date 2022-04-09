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
       * @var array 
      */
      protected $includePaths = [];




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
       * Cache content
       *
       * @param string $cachePath
       * @param $content
       * @return false|int
      */
      public function cache(string $cachePath, $content)
      {
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
       * @param string $content
       * @return bool
      */
      public function cacheTemplate(string $template, string $content): bool
      {
           $cachePath = $this->loadTemplateCachePath($template);

           return $this->cache($cachePath, $content);
      }




      /**
       * @param string $template
       * @param $content
       * @return false|int
      */
      public function cacheIncludeTemplate(string $template, $content)
      {
            $cachPath = $this->loadIncludeTemplate($template);
            
            return $this->cache($cachPath, $content);
      }




      /**
       * @param string $template
       * @return string
      */
      public function loadIncludeTemplate(string $template)
      {
            return $this->getCacheDir() . DIRECTORY_SEPARATOR . "/includes/". md5($template). '.php';
      }


      
      
      /**
       * @param string $template
       * @return string
      */
      public function loadTemplateCachePath(string $template): string
      {
            return $this->getCacheDir() . DIRECTORY_SEPARATOR . md5($template). '.php';
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